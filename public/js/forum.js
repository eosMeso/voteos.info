/*jshint esversion: 6 */


window.myEOS = {};

document.addEventListener('scatterLoaded', scatterExtension => {
    // Scatter will now be available from the window scope.
    // At this stage the connection to Scatter from the application is
    // already encrypted.
    const scatter = window.scatter;

    // It is good practice to take this off the window once you have
    // a reference to it.
    window.scatter = null;

    const network = {
        protocol:EOS_PROT,
        host:    EOS_NODE,
        port:    EOS_PORT,
        blockchain: 'eos',
        chainId: 'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906'
    }

    var callback = function(eos, account) {
        $('.accountName').text(account.name);
        $('.accountStaked').text(account.staked);

        $('input[name="data[User][name]"]').val(account.name);
        $('input[name="data[User][stake]"]').val(account.staked);
    };

    scatter.getIdentity({accounts: [network]}).then(identity => {
        scatter.authenticate()
            .then(sig => {
                const account = scatter.identity.accounts.find(x => x.blockchain === 'eos');
                const eosOptions = {
                    authorization: [account.name +'@'+ account.authority],
                    chainId:       network.chainId,
                };
                const eos = scatter.eos( network, Eos, eosOptions, 'http' );


                eos.getAccount({account_name: account.name}).then(accountInfo => {
                    var staked = (accountInfo.net_weight + accountInfo.cpu_weight) /(10 *1000);
                    account.staked = staked;
                    callback(eos, account);

                });

                eos.contract('eosmesoforum').then(backend => {
                    window.myEOS = {
                        eos: eos,
                        eosOptions: eosOptions,
                        backend:    backend,
                        account:    account,
                    };
                });
            });
    }).catch(function() {
        disableos();
    });
});

async function post(message, parent) {
    if (!message) throw "Empty message";
    backend = window.myEOS.backend;
    var post_uuid = POST_ID;
    var response = await backend.post({
        "account":     window.myEOS.account.name,
        "title":       (parent && parent.transaction) ? '' : POST_NAME,
        "content":     message,
        "reply_to_tx": (parent && parent.transaction) ? parent.transaction : '',
        "json_meta":   ""
    }, window.myEOS.eosOptions);
    var approved = ((response.broadcast === true) && response.transaction_id);
    return approved;
}

async function vote(post, vote) {
    backend = window.myEOS.backend;
    var response  = false;
    try {
        response = await backend.vote({
            voter:         window.myEOS.account.name,
            tx:            post.transaction,
            vote_value:    vote,
            json_meta:  JSON.stringify({
                commentId: post.id,
            })
        }, window.myEOS.eosOptions);
    } catch (error) {
        alert(error);
        return false;
    }
    var approved = ((response.broadcast === true) && response.transaction_id);
    return approved;
}


$(function() {
    $(document).on('show.bs.modal', function (event) {
        var modal  = $(this);
        var button = $(event.relatedTarget);
        var parent = button.data('parent');
        var form = $('form', modal);
        $(form, modal).data('parent', parent);
        console.log(parent);
        modal.find('.comment').html(parent.description);
        modal.find('[name="data[Comment][parent_id]"]').val(parent.id);
    });

    $(document).on('submit', 'form', async function(event) {
        var form = event.target;
        if ( !$(form).data('transaction')) {
            event.preventDefault();
            var comment  = $(form).find('[name="data[Comment][description]"]').val();
            var response = await post(comment, $(form).data('parent'));
            if (response) {
                $(form).data('transaction', response);
                $(form).find('[name="data[Comment][transaction]"]').val(response);
                $(form).submit();
            }
        }
        return $(form).data('transaction');
    });

    $(document).on('click', '.vote4comment', async function(event) {
        event.preventDefault();
        var link        = this;
        var data        = $(this).data();
        var sumOld      = $('.sum', link).html();
        $('.sum', link).html('<i class = "fa fa-spinner fa-spin"></i>');
        var transaction = await vote(data.comment, data.vote);
        if (!transaction) {
            $('.sum', link).html(sumOld);
        } else {
            $.post('/votes4comments', {transaction: transaction,}, function(data) {
                $('.sum', link).html(data);
            });
        }
    });

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $('[title]').tooltip();
});