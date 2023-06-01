<template>
    <div v-if="current_page && last_page" class="paginator mb-3">
        <button class="btn btn-primary me-1" @click="emitPaginatorClickedEvent(first_page_url)" :disabled="first_page_url==null"> &lt;&lt; </button>
        <button class="btn btn-primary me-1" @click="emitPaginatorClickedEvent(prev_page_url)" :disabled="prev_page_url==null"> &lt; </button>
        <span class="me-1">Page: {{ current_page }}/{{ last_page }}</span>
        <button class="btn btn-primary me-1" @click="emitPaginatorClickedEvent(next_page_url)" :disabled="next_page_url==null"> &gt; </button>
        <button class="btn btn-primary me-1" @click="emitPaginatorClickedEvent(last_page_url)" :disabled="last_page_url==null"> &gt;&gt; </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            current_page: null,
            last_page: null,
            first_page_url: null,
            last_page_url: null,
            next_page_url: null,
            prev_page_url: null
        };
    },
    methods: {
        emitPaginatorClickedEvent(url) {
            let urlObject = new URL(url);
            let page = urlObject.searchParams.get('page');
            window.eventEmitter.emit('paginatorClicked', page);
        }
    },
    mounted() {
        let self = this;
        window.eventEmitter.on('gotListData', function(data) {
            self.current_page = data.current_page;
            self.last_page = data.last_page;
            self.first_page_url = data.first_page_url;
            self.last_page_url = data.last_page_url;
            self.next_page_url = data.next_page_url;
            self.prev_page_url = data.prev_page_url;
        });
    }
}
</script>
