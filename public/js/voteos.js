/*jshint esversion: 6 */


window.myEOS = {};


function disableos() {
    $('.eos').attr('disabled', 'disabled');
    $('.eos').prop('disabled', 'disabled');
    $('.eos').addClass('disabled');
    $('.eos').prop('title', 'Scatter is not installer or enabled for you to submit.');
    $(document).on('click', '.eos', async function(event) {
        $('[title]').tooltip('toggle');
        event.preventDefault();
    });
    $('.account').hide();
    $('[title]').tooltip();
    $('.no-eos').removeClass('d-none');
}