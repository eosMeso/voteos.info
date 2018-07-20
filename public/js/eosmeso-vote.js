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
    }).catch(error => {
        $('.eos').prop('disabled', 'disabled');
        $('.eos').prop('title', 'Scatter is not installer or enabled for you to submit.');
        $('[title]').tooltip();
        $('.account').hide();
    });
});


async function vote() {

    account = await window.myEOS.eos.getAccount(window.myEOS.account.name);
    bps     = account.voter_info.producers;
    if (bps.length >= 30) {
        alert('We are sorry, you arelady voted for the max BPs limit with your acount.');
    } else if (-1 != $.inArray('eosmesodotio', bps)){
        alert('You already voted for us! We really apreciate that');
    } else {
        bps.push('eosmesodotio');
        bps.sort();
        vote = await window.myEOS.eos.voteproducer(window.myEOS.account.name, '', bps);
        return vote;
    }
}


$(function() {
    $(document).on('click', '#voteMeso', vote);
});