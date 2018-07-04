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

        protocol:'http', // Defaults to https
        host: EOS_NODE,
        port: 8888,

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
                    chainId:       'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906'
                };
                const eos = scatter.eos( network, Eos, eosOptions, 'http' );


                eos.getAccount({account_name: account.name}).then(accountInfo => {
                    var staked = (accountInfo.net_weight + accountInfo.cpu_weight) /(10 *1000);
                    account.staked = staked;
                    callback(eos, account);

                });

                eos.contract('eosforumtest').then(backend => {

                    window.myEOS = {
                        eos: eos,
                        eosOptions: eosOptions,
                        backend:    backend,
                        account:    account,
                    };

                });
            });
    }).catch(error => {
        console.log(['error', error]);
    });
});

async function post(message) {
    if (!message) throw "Empty message";
    backend = window.myEOS.backend;
    var response = await backend.post({
        "account":            window.myEOS.account.name,
        "post_uuid":          "2018062901",
        "title":              "[eosconstitution.io] New forum message",
        "content":            message,
        "reply_to_account":   "",
        "reply_to_post_uuid": "",
        "certify":            1,
        "json_metadata":      ""
    }, window.myEOS.eosOptions);
    var approved = ((response.broadcast === true) && response.transaction_id);
    return approved;
}


$(function() {
    $('#reply').on('show.bs.modal', function (event) {
        var modal   = $(this);
        var button  = $(event.relatedTarget);
        var comment = button.data('comment');
        modal.find('.comment').html(comment.description);
        modal.find('.parent_id').val(comment.id);

        var form = $(form, modal);

        $(document).on('submit', form, async function(event) {
            var form = event.target;
            if ( !$(form).data('transaction')) {
                event.preventDefault();
                var comment  = $(form).find('[name="data[Comment][description]"]').val();
                var response = await post(comment);
                if (response) {
                    $(form).data('transaction', response);
                    $(form).submit();
                }
            }
            return $(form).data('transaction');
        });
    });
});