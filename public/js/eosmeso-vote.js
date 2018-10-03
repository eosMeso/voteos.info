/*jshint esversion: 6 */

window.myEOS = {};

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

async function vote() {

    account = await window.myEOS.eos.getAccount(window.myEOS.account.name);
    bps     = account.voter_info.producers;
    if (bps.length >= 30) {
        alert('We are sorry, you arelady voted for the max BPs limit with your acount.');
    } else if (-1 != $.inArray('eosmesodotio', bps)){
        alert('You already voted for us! We really apreciate that.');
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