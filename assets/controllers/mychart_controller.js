import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:pre-connect', this.onPreConnect);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener(
            'chartjs:pre-connect',
            this.onPreConnect
        );
    }

    // eslint-disable-next-line class-methods-use-this
    onPreConnect(event) {
        function convertUnixTimestampToReadable(value) {
            const date = new Date(value * 1000); // multiply by 1000 because this function uses milliseconds compared to return value from php strtotime() which uses seconds.
            const hoursString = date.getUTCHours().toString().padStart(2, 0);
            const minutesString = date
                .getUTCMinutes()
                .toString()
                .padStart(2, 0);
            const secondsString = date
                .getUTCSeconds()
                .toString()
                .padStart(2, 0);
            const millisecondsString = date
                .getUTCMilliseconds()
                .toString()
                .padStart(3, 0);
            return `${hoursString}:${minutesString}:${secondsString}.${millisecondsString}`;
        }

        // For instance you can format Y axis
        // eslint-disable-next-line no-param-reassign
        event.detail.options.scales = {
            y: {
                ticks: {
                    // eslint-disable-next-line
                    callback(value, index, ticks) {
                        return convertUnixTimestampToReadable(value);
                    },

                    color: '#fae8e0',
                },

                grid: {
                    display: true,
                    color: '#fae8e0',
                },
            },

            x: {
                ticks: {
                    color: '#fae8e0',
                },

                grid: {
                    display: true,
                    color: '#fae8e0',
                },
            },
        };

        // eslint-disable-next-line no-param-reassign
        event.detail.options.plugins = {
            tooltip: {
                callbacks: {
                    label(context) {
                        return convertUnixTimestampToReadable(context.parsed.y);
                    },

                    afterBody(context) {
                        return context[0].raw.extraNotes;
                    },
                },
            },

            legend: {
                labels: {
                    color: '#fae8e0',
                },
            },
        };
    }
}
