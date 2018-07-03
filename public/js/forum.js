/*jshint esversion: 6 */

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
        // host: 'node.eosmeso.io',
        // host: 'peers.eosbp.network',
        host: 'test.eosbp.network',
        port: 8888,

        blockchain: 'eos',
        chainId: 'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906'
    }

    var callback = function(eos, account) {

        console.log(account);
        $('.accountName').text(account.name);
        $('.accountStaked').text(account.staked);

    }

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
                    backend.post({
                        "account":            "guytmobzgige",
                        "post_uuid":          "2018062901",
                        "title":              "[eosconstitution.io] New forum message",
                        "content":            "This is a test to a message sent to the forum. \n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Duo Reges: constructio interrete. Ita relinquet duas, de quibus etiam atque etiam consideret. Quamquam te quidem video minime esse deterritum. ",
                        "reply_to_account":   "",
                        "reply_to_post_uuid": "",
                        "certify":            1,
                        "json_metadata":      ""
                    }, eosOptions).then(response => {
                        console.log(response);
                    });
                });
            });
    }).catch(error => {
        console.log(['error', error]);
    });
})