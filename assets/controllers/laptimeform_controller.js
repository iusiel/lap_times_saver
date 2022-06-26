import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    // eslint-disable-next-line class-methods-use-this
    connect() {
        if (typeof jQuery !== 'undefined') {
            $(document).ready(() => {
                $('.js-select2').select2();
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        }
    }
}
