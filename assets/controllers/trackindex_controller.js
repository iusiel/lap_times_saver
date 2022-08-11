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
        if (localStorage.getItem('refreshTrackDropdown')) {
            // send message to broadcast channel so that lap time page can update the car dropdown list
            const bc = new BroadcastChannel('test_channel');
            bc.postMessage('refresh-track-dropdown');
            localStorage.removeItem('refreshTrackDropdown');
        }

        // close window if it was opened via javascript
        if (localStorage.getItem('fromLaptimeForm')) {
            localStorage.removeItem('fromLaptimeForm');
            window.close();
        }
    }
}
