#include <eosiolib/eosio.hpp>
#include <string>

using eosio::name;

class eosmesoforum : public eosio::contract {
    public:
        eosmesoforum(account_name self)
        :eosio::contract(self)
        {}

        /**
         * Post content to the forum. The content can be a proposal, a comment or a reply.
         *
         * @param account     Who posted the content.
         * @param title       Title if the post, empty if it's a reply.
         * @param content     The actual content of the post.
         * @param reply_to_tx In case it's a reply, or empty if not.
         * @param json_meta   Metadata to link to the transaction.
         *
         * @abi
         */
        void post(
                const account_name account,
                const std::string& title,
                const std::string& content,
                const std::string& reply_to_tx,
                const std::string& json_meta
        ) {
            require_auth(account);

            eosio_assert(title.size()   < 128, "title should be less than 128 characters long.");
            eosio_assert(content.size() > 0,   "content should be more than 0 characters long.");
            eosio_assert(content.size() < 1024 * 1024 * 10, "content should be less than 10 KB long.");

            if (reply_to_tx.size() != 0) {
                eosio_assert(title.size() == 0,        "If the post is a reply, there should not be a title.");
                eosio_assert(reply_to_tx.size() == 64, "reply_to_tx should be  of 64 characters.");
            }

            if (json_meta.size() != 0) {
                eosio_assert(json_meta[0] == '{', "json_meta must be a JSON object (if specified).");
                eosio_assert(json_meta.size() < 8192, "json_meta should be shorter than 8192 bytes.");
            }
        }


        /**
         * Post a message to the forum. The message can be a proposal, a commnet or a reply.
         *
         * @param account     Who voted
         * @param tx          The transaction the user voted for
         * @param vote_value  Vote value (yes/no)
         * @param json_meta   Metadata to link to the transaction.
         *
         * @abi
         */
        void vote(
                const account_name voter,
                const std::string& tx,
                const std::string& vote_value,
                const std::string& json_meta
        ) {
            require_auth(voter);
            eosio_assert(tx.size() == 64,       "reply_to_tx should be  of 64 characters.");
            eosio_assert(vote_value.size() > 0, "Vote value should be at least 1 character.");
            eosio_assert(vote_value.size() < 128, "Vote value should be less than 128 characters long.");

            if (json_meta.size() != 0) {
                eosio_assert(json_meta[0] == '{', "json_meta must be a JSON object (if specified).");
                eosio_assert(json_meta.size() < 8192, "json_meta should be shorter than 8192 bytes.");
            }
        }
};

EOSIO_ABI( eosmesoforum, (post)(vote) )
