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
        $('.eos').prop('disabled', 'disabled');
        $('.eos').prop('title', 'Scatter is not installer or enabled for you to submit.');
        $('[title]').tooltip();
        $('.account').hide();
    });
});

/**
 *
 * @param {                const account_name ,
                const name         proposal_name,
                const std::string& title,
                const std::string& proposal_json} message
 * @param {*} parent
 */
async function post(data) {


    backend = window.myEOS.backend;
    var r =     await backend.unpropose({
        proposer:      window.myEOS.account.name,
        proposal_name: data.name,
    }, window.myEOS.eosOptions);
    console.log(r);
    return false;


    backend = window.myEOS.backend;
    var response = await backend.propose({
        proposer:      window.myEOS.account.name,
        proposal_name: data.name,
        title:         data.title,
        proposal_json: JSON.stringify({
            type:        data.type,
            description: data.description,
            content:     data.content,
        }),
    }, window.myEOS.eosOptions);
    var approved = ((response.broadcast === true) && response.transaction_id);
    console.log(approved);
    return approved;
}


$(function() {
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $('[title]').tooltip();

    $(document).on('submit', 'form', async function(event) {
        var form = event.target;
        if ( !$(form).data('transaction')) {
            event.preventDefault();
            var data = {
                title:       $(form).find('[name="data[Proposal][title]"]').val(),
                type:        $(form).find('[name="data[Proposal][type]"]').val(),
                name:        $(form).find('[name="data[Proposal][name]"]').val(),
                description: $(form).find('[name="data[Proposal][description]"]').val(),
                content:     $(form).find('[name="data[Proposal][content]"]').val(),
            }
            var response = await post(data);
            console.log(response);
            return false;
            if (response) {
                $(form).data('transaction', response);
                $(form).find('[name="data[Comment][transaction]"]').val(response);
                $(form).submit();
            }
        }
        return $(form).data('transaction');
    });
});