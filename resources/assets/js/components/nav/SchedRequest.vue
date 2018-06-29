<template>
 
    <div>
        <!-- v-for="sched in schedrequest" -->
    <form method="post" action="/approve-schedules-search-results">{{csrf_field()}}
        <input type="hidden" name="q" value="am">
        <input type="submit" class="btn btn-link" value="View">
    </form>
    </div>

</template>

<script>
    export default {
        props: [
            'schedrequest'
        ],
        mounted() {
            console.log('Component mounted.');
        },
        created(){
            
        }
    }
            /*
            Echo.channel('channelExampleEvent')
            .listen('eventTrigger', (e) => {
                alert('Event has been triggered');
                console.log(e);
            });*/
</script>
