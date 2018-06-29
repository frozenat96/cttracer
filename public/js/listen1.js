
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');
if(token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found.'); 
}
//window.axios.defaults.common['X-Requested-With'] = 'XMLHttpRequest';

const app = new Vue({
    el: '#app',
    data: {
        schedrequest: '',
    },
    created() {
        axios.post('/notify-sched-request').then(response => {
            this.schedrequest = response.data;
        }).catch(error => {
            console.log(error.message);
        });
        /*
        Echo.private('channelExampleEvent')
            .listen('eventTrigger', (e) => {
                alert('Event has been triggered');
                console.log(e);
        });*/
    }
});