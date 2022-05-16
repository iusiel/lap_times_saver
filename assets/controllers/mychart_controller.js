import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:pre-connect', this._onPreConnect);
        this.element.addEventListener('chartjs:connect', this._onConnect);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('chartjs:pre-connect', this._onPreConnect);
        this.element.removeEventListener('chartjs:connect', this._onConnect);
    }

    _onPreConnect(event) {
        // The chart is not yet created
        console.log(event.detail.options); // You can access the chart options using the event details

        // For instance you can format Y axis
        event.detail.options.scales = {
            y: {
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, ticks) {
                        const date = new Date(value * 1000); // multiply by 1000 because this function uses milliseconds compared to return value from php strtotime() which uses seconds.
                        const hoursString = date.getUTCHours().toString().padStart(2,0);
                        const minutesString = date.getUTCMinutes().toString().padStart(2,0);
                        const secondsString = date.getUTCSeconds().toString().padStart(2,0);
                        const millisecondsString = date.getUTCMilliseconds().toString().padStart(3,0);
                        return `${hoursString}:${minutesString}:${secondsString}.${millisecondsString}`;
                    }
                }
            }
        };
    }
}