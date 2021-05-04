<template>
    <form ref="registerForm">
        <div>
                <p v-show="errors.length > 0">Fix the following errors:</p>
                <ul class="text-danger" v-for="error in errors" :key="error">
                    <li>{{error}}</li>
                </ul>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="consumerKey">Consumer Key</label>
                <input type="text" v-model="consumerKey" class="form-control" id="consumerKey" placeholder="Consumer Key" required>
            </div>
            <div class="form-group col-md-6">
                <label for="consumerSecret">Consumer Secret</label>
                <input type="text" v-model="consumerSecret" class="form-control" id="consumerSecret" placeholder="Consumer Secret" required>
            </div>
        </div>
        <div class="form-group">
            <label for="shortcode">Shortcode</label>
            <input type="shortcode" v-model="shortcode" class="form-control" id="shortcode" placeholder="Shortcode" required>
        </div>
        <div class="form-group">
            <label for="authenticationUrl">Authentication URL</label>
            <input type="authenticationUrl" v-model="authenticationUrl" class="form-control" id="authenticationUrl" placeholder="Authentication URL" required>
        </div>

        <div class="form-group">
            <label for="registerUrl">Register URL</label>
            <input type="text" v-model="registerUrl" class="form-control" id="registerUrl" placeholder="Register URL" required>
        </div>
        <div class="form-row mt-5">
            <div class="form-group col-md-4">
                <button type="submit" v-on:click="registerURL" class="btn btn-primary">Register Url</button>
            </div>
            <div class="form-group col-md-8">
                <button v-on:click="authenticationToken" class="btn btn-primary">Get authentication token</button>
                <small class="text-success" v-text="token"></small>
            </div>

        </div>
    </form>
</template>
<script>
import axios from "axios";
export default {
    data(){
        return{
            consumerKey:this.ck,
            consumerSecret:this.cs,
            authenticationUrl:this.aurl,
            registerUrl:this.vurl,
            shortcode:this.sc,
            errors:[],
            token:""
        }
    },
    props:['ck','cs','sc','vurl','aurl'],
    methods:{
        authenticationToken:function(event){
            this.errors = [];
            console.log(this.consumerKey);
            event.preventDefault();
            let url = this.authenticationUrl;

            let data = {
                url: url,
                consumerKey: this.consumerKey,
                consumerSecret:this.consumerSecret,
                registerUrl:this.registerUrl,
                shortcode:this.shortcode,
                '_token':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
            let options = {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            }
            let status = 200;
            fetch('/getAuthToken', options)
                .then((response) =>{
                    status = response.status
                    return response.json()
                })
                .then((data)=>{
                    if(status === 422){
                        Object.keys(data).forEach((key)=> {
                            this.errors.push(`Please input the ${key=='url'?'Authentication URL':key}`)
                        });
                    }else{
                        if(data.statusC == 0){
                            this.errors.push(data.errorMessage);
                        }else{
                            this.token =  data.token;
                        }
                    }
                })
                .catch((err)=>{
                    console.log(err);
                    this.errors.push('Server error. Please try again');
                });

        },

        registerURL:function(event){
            this.errors = [];
            event.preventDefault();
            let url = this.authenticationUrl;

            let data = {
                url: url,
                registerUrl:this.registerUrl,
                consumerKey: this.consumerKey,
                consumerSecret:this.consumerSecret,
                shortcode:this.shortcode,
                '_token':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
            let options = {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            }
            let status = 200;
            fetch('/registerURL', options)
                .then((response) =>{
                    status = response.status
                    return response.json()
                })
                .then((data)=>{
                    if(status === 422){
                        Object.keys(data).forEach((key)=> {
                            this.errors.push(`Please input the ${key=='url'?'Authentication URL':key}`)
                        });
                    }else{
                        if(data.statusC == 0){
                            this.errors.push(data.errorMessage);
                        }else{
                            location.reload();
                        }
                    }
                })
                .catch((err)=>{
                    this.errors.push('Server error. Please try again');
                });
        }
    }
}

</script>
