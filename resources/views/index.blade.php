<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Page ranks') }}</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>
        <div class="container">
            <h1>{{ __('Page ranks') }}</h1>
            <div id="app">
                <div class="row mb-3">
                    <div class="col-1 pt-1">
                        <div class="label-for-search">{{ __('Search') }}:</div>
                    </div>
                    <div class="col-11">
                        <input type="text" class="form-control" v-model="search_text" v-on:keyup="performSearch">
                    </div>
                </div>
                <div class="table-responsive" v-if="domains.length>0">
                    <table class="table table-bordered table-with-bordered-cells">
                        <thead>
                        <tr>
                            <th>{{ __('Domain') }}</th>
                            <th>{{ __('Rank') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="domain in domains">
                            <td v-text="domain.root_domain"></td>
                            <td v-text="domain.rank"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-show="domains.length>0">
                    <paginator></paginator>
                </div>
                <div v-if="domains.length === 0">{{ __('No records') }}.</div>
            </div>
            <script type="text/javascript">
                window.createVueApp({
                    data() {
                        return {
                            domains: [],
                            search_text: ''
                        }
                    },
                    mounted() {
                        this.getDomainsList(1);
                        let self = this;
                        window.eventEmitter.on('paginatorClicked', function(page){
                            self.getDomainsList(page);
                        });
                    },
                    methods: {
                        getDomainsList(page) {
                            let self = this;
                            self.loading = true;
                            window.axios.get('/api/page-ranks/get-list', {
                                params: {
                                    page: page,
                                    search_text: self.search_text
                                }
                            }).then(function(response){
                                self.domains = response.data.data;
                                window.eventEmitter.emit('gotListData', response.data);
                            });
                        },
                        performSearch() {
                            let delayTimer;
                            let self = this;
                            clearTimeout(delayTimer);
                            delayTimer = setTimeout(function() {
                                self.getDomainsList(1);
                            }, 1000);
                        }
                    }
                }).component('paginator', window.vueComponents.paginator)
                    .mount('#app');
            </script>
        </div>
    </body>
</html>
