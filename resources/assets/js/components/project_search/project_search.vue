<template>
    <div class="container">
    <h2>Hello world</h2>
    <input type="text" class="form-control" name="search" v-model="search"/>
    <button class="btn btn-danger" v-on:click="hasSearched()">Search</button>
    <table>
            <thead>
                <tr>
                    <th>Project No.</th>
                    <th>Project Name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="project in projects.data">
                    <td>{{project.projNo}}</td>
                    <td>{{project.projName}}</td>
                </tr>    
            </tbody>
    </table>
    <div class="pagination">
        <button class="btn btn-default" v-on:click="fetchPaginateProjects(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">previous</button>
        <span>Page {{pagination.current_page}} of {{pagination.last_page}}</span>
        <button class="btn btn-default" v-on:click="fetchPaginateProjects(pagination.next_page_url)" :disabled="!pagination.next_page_url">next</button>
    </div>

        <ul>
            <li v-for="n in pagination.total">{{ n }}>
                <a v-on:click="fetchPaginateProjects(pagination.path+'?search='+search+'&page='+n)">{{n}}</a>
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        
        data() {
            return {
                projects:{},
                search:'',
                url: '/project-search-r',
                searchurl: '/project-search-r',
                pagination: []
            }
        },
        mounted() {
            //this.getDataProject();
            console.log('Component mounted.')
        },
        created:function(){
            
        },
        methods: {
            getDataProject() {
                let $this = this
                axios.get(this.url)
                    .then(response => {
                        console.log(response);
                        this.projects = response.data;
                        $this.makePagination(response.data);
                    })
            },
            hasSearched(){
                this.url = this.searchurl;
                this.fetchDataProject();
            },
            fetchDataProject() {
                let $this = this
                axios.get(this.url+'?search='+this.search)
                    .then(response => {
                        console.log(response);
                        this.projects = response.data;
                        $this.makePagination(response.data);
                    })
            },
            makePagination(data){
                let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                    path: data.path,
                    total: data.total,
                }

                this.pagination = pagination;
            },
            fetchPaginateProjects(url){
                this.url = url;
                this.getDataProject();
            },
            pages(p){
            var total = Math.ceil(p.total / 10);
            var arr = [], i=1;
            while(total--){
                arr.push(i);
                i++;
            }
            var min = this.page() - (this.page()%10);		
            if(p.current_page()%10==0) {
                if((p.current_page - 10) > 0){
                    min-=10;
                }     
            }
            var max = min +10;
            
            var arr = slice(arr, min, max);
            this.minPage = min;
            this.maxPage = max;

            return arr;
            }
        }
    }
</script>
