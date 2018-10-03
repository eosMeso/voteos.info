scatter.connect("Voteos.info").then(function(connected){

    // User does not have Scatter.
    if(!connected) return false;

    const scatter = window.scatter;
    window.scatter = null;

    const network = {
        blockchain: 'eos',
        host:       EOS_NODE,
        port:       EOS_PORT,
        protocol:   EOS_PROT,        
        chainId: 'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906'
    };

    const requiredFields = {
        accounts: [network]
    };

    var callback = function(eos, account) {
        $('.accountName').text(account.name);
        $('.accountStaked').text(account.staked);

        $('input[name="data[User][name]"]').val(account.name);
        $('input[name="data[User][stake]"]').val(account.staked);
    };

    scatter.getIdentity(requiredFields).then(identity => {
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

async function post(data) {
    backend = window.myEOS.backend;
    var response = await backend.post({
        account:     window.myEOS.account.name,
        title:       data.title,
        content:     data.content,
        reply_to_tx: '',
        json_meta: JSON.stringify({
            type:        data.type,
            description: data.description,
        }),
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
                proposalId: post.id,
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
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $('[title]').tooltip();

    setTimeout(function () {
        if (window.myEOS.account === undefined) {
            disableos();
        }
    }, 5000);

    document.addEventListener('scatterLoaded', scatterExtension => {

        $(document).on('submit', 'form', async function(event) {
            var form = event.target;
            if ( !$(form).data('transaction')) {
                event.preventDefault();
                var data = {
                    title:       $(form).find('[name="data[Proposal][title]"]').val(),
                    type:        $(form).find('[name="data[Proposal][type]"]').val(),
                    description: $(form).find('[name="data[Proposal][description]"]').val(),
                    content:     $(form).find('[name="data[Proposal][content]"]').val(),
                }
                var response = await post(data);
                if (response) {
                    $(form).data('transaction', response);
                    $(form).find('[name="data[Proposal][transaction]"]').val(response);
                    $(form).submit();
                }
            }
            return $(form).data('transaction');
        });

        $(document).on('click', '.vote4proposal', async function(event) {
            event.preventDefault();
            console.log('voting...');
            var link        = this;
            var data        = $(this).data();
            var sumOld      = $('.sum', link).html();
            $('.sum', link).html('<i class = "fa fa-spinner fa-spin"></i>');
            var transaction = await vote(data.proposal, data.vote);
            if (!transaction) {
                $('.sum', link).html(sumOld);
            } else {
                $.post('/votes4proposals', {transaction: transaction,}, function(data) {
                    $('.sum', link).html(data);
                });
            }
        });
    });
});