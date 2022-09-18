<script>
import getBaseUrl from '../services/GetBaseUrl.js';

export default {
    data() {
        return {
            currentRoute: window.location.href,
            gameLink: `${getBaseUrl()}game/`,
            carLink: `${getBaseUrl()}car/`,
            trackLink: `${getBaseUrl()}track/`,
            laptimeLink: `${getBaseUrl()}lap_time/`,
            summaryLink: `${getBaseUrl()}lap_time/summary`,
            logoutLink: `${getBaseUrl()}logout/`,
        };
    },
};
</script>

<template>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><span class="h2">LTS</span></a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a
                            v-bind:class="[
                                'nav-link',
                                currentRoute === summaryLink ? 'active' : '',
                            ]"
                            aria-current="page"
                            v-bind:href="summaryLink"
                            >Summary</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            v-bind:class="[
                                'nav-link',
                                currentRoute.indexOf(laptimeLink) !== -1 &&
                                currentRoute !== summaryLink
                                    ? 'active'
                                    : '',
                            ]"
                            aria-current="page"
                            v-bind:href="laptimeLink"
                            >Lap Times</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            v-bind:class="[
                                'nav-link',
                                currentRoute.indexOf(gameLink) !== -1
                                    ? 'active'
                                    : '',
                            ]"
                            aria-current="page"
                            v-bind:href="gameLink"
                            >Games</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            v-bind:class="[
                                'nav-link',
                                currentRoute.indexOf(carLink) !== -1
                                    ? 'active'
                                    : '',
                            ]"
                            aria-current="page"
                            v-bind:href="carLink"
                            >Cars</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            v-bind:class="[
                                'nav-link',
                                currentRoute === trackLink ? 'active' : '',
                            ]"
                            aria-current="page"
                            v-bind:href="trackLink"
                            >Tracks</a
                        >
                    </li>
                </ul>
                <a class="nav-link" v-bind:href="logoutLink">Logout</a>
            </div>
        </div>
    </nav>
</template>
