@extends('layouts.app')

@section('title', 'proposals')

@section('content')

<h1>About voteos.info</h1>

<p>
    <a href="https://eosmeso.io" target="_blank">
        <img src="https://eosmeso.io/Logo_eosMeso256.png" class="img-thumbnail float-right ml-2" />
    </a>
    voteos.info is a project created by the
    <a href="https://eosmeso.io" target="_blank">eosMeso Block Producer</a> team.
    We are based in MesoAmerica, Latin America and want to use EOS to usher in a
    new era of transparency, accountability and prosperity in our region.
</p>

<p>
    We created this tool in order to save time and effort, to help the EOS community
    use our powerful blockchain to discover what we want as a community.  Currently,
    there are multiple discussion forums, chat groups, articles trying to persuade
    the multiple members of the community to follow a given development path.  It
    can be overwhelming to follow all of these and even harder to understand where
    there is consensus and where there is disagreement.  We want to give the community
    a way to discuss matters on chain, putting their stake where their mouth is.
</p>

<p>
    Delegated Proof of Stake is a consensus that can lead to our shared dream of a
    blockchain where each voluntary tokenholder has a voice, discussions are transparent
    and the community as a whole decides.  In order for the dream to be realized, we
    need an informed and conscientious community. By participating with us and using
    this tool, we can make the dream come true.  We wish to give every member of the EOS
    community a voice and give opinions their due weight.  We wish to give EOS holders a
    better way to dialogue and reach consensus on difficult subjects. We want the best
    ideas to emerge based on the merit they receive from tokenholders and not on the
    insistence of the loudest person in the chat.  Here is your chance to log on the
    chain your ideas and upvote the proposals you believe are best, understand the
    opinion of others and hold them to account based on what they have supported on-chain.
    Have a great idea? Create your own proposal and see how much support for it you receive
    from other EOS community members!
</p>

<h2>How it works</h2>

<p>
    We use <a href="https://get-scatter.com/" target="_blank">Scatter</a> to validate your
    eos account. Every proposal and every comment is signed in the eosio blockchain as a
    transaction to our
        <a href="https://github.com/eosMeso/voteos.info/tree/master/resources/dapp-backend/eosmesoforum.cpp" target="_blank" class="text-monospace">
            eosmesoforum
        </a>
    dapp backend / smart contract (
        <a href="https://steemit.com/eos/@stuardo/redefining-the-blockchain-terminology-in-eosio">
            and why we don‘t call it smart contract
        </a>).
    We use a database to store all transactions and their content as a cache
    to make it easier and faster to load the content from chain and show you the content
    in a friendly maner. You should be able to see a link to the transaction in every single
    post for you to corroborate any data.
</p>


<h2>The vision</h2>

<p>
    At the moment <a href="https://voteos.info">voteos.info</a> is about tallying the token
    support for given proposals. With time we will integrate the tool to the larger ecosystem
    in order to make it possible for any given proposal to become binding and transform from
    a simple “poll” into a full fledged referendum, worker proposal or other on chain decision.
    We wish to have voteos.info become a tool that the community can use to cast any type of
    vote, from Block Producer candidates, to referenda, all the way to simple
    <a href="{{ url('/proposals/create') }}">
        “what does EOS community think about ______?”
    </a>
</p>

<p class="text-center">Use your voice, stay informed. Voteos!</p>


@endsection