<template>
 
    <div>
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      
        <span class="badge badge-pill badge-success"></span>
          
        <i class="fas fa-project-diagram"></i> Notifications</a>
        <div>
        <form method="post" action="/approve-schedules-search-results">
            <label> </label>
            <input type="hidden" name="q" value="am">
            <input type="submit" class="btn btn-link" value="View">
        </form>
        </div>    
    </div>


</template>

<script>

    export default {
       data() {
            return {
                notifications:{},
            }
        },
        created() {
           this.getNotifications();
           
        },
        methods: {
           getNotifications() {
                axios.post('/notify-sched-request').then(response => {
                this.notifications = response.data;
                console.log(this.notifications.length);
                }).catch(error => {
                    console.log(error.message);
                });
           }
        },
    }
</script>
