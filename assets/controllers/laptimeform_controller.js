import { Controller } from '@hotwired/stimulus';
import getBaseUrl from '../js/services/GetBaseUrl.js';
import JSONFetchClient from '../js/services/JSONFetchClient.js';

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

        if (document.getElementById('newLapTimeContainer')) {
            // upon page load, set default values of form based on the last submission
            if (localStorage.getItem('tempFormData') !== null) {
                const tempFormData = JSON.parse(
                    localStorage.getItem('tempFormData')
                );

                Object.entries(tempFormData).forEach((element) => {
                    const [key, value] = element;
                    document.querySelector(`[name="${key}"]`).value = value;
                });
            }

            const lapTimeForm = document.getElementById('lapTimeForm');
            lapTimeForm.addEventListener('submit', () => {
                const tempFormData = {};
                const formData = new FormData(lapTimeForm);
                // eslint-disable-next-line no-restricted-syntax
                for (const [key, value] of formData) {
                    if (key !== 'lap_time[_token]') {
                        tempFormData[key] = value;
                    }
                }
                localStorage.setItem(
                    'tempFormData',
                    JSON.stringify(tempFormData)
                );
            });
        }

        if (document.querySelectorAll('.create_new_link').length > 0) {
            document.querySelectorAll('.create_new_link').forEach((element) => {
                element.addEventListener('click', (event) => {
                    event.preventDefault();
                    localStorage.setItem('fromLaptimeForm', true);
                    window.open(event.target.href, '_blank');
                });
            });
        }

        const bc = new BroadcastChannel('test_channel');
        bc.onmessage = (event) => {
            if (event.data === 'refresh-car-dropdown') {
                JSONFetchClient(`${getBaseUrl()}car/last`).then((response) => {
                    const newOption = new Option(
                        response.name,
                        response.id,
                        true,
                        true
                    );
                    $('#lap_time_Car').append(newOption).trigger('change');
                });
            }
        };
    }
}
