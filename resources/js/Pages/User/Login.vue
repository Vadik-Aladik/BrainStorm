<script>
import { router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

export default{
    name: 'Login',
    components:{
        Link
    },
    data(){
        return{
            email: '',
            password: '',

            email_error: '',
            password_error: '',
        }
    },
    methods:{
        async login(){
            const res = await axios.post('/login', {
                email: this.email,
                password: this.password
            }).then((res)=>{
                console.log(res);
            }).catch((error)=>{
                console.log(error);
                this.email_error = error.response.data.errors.email;
                this.password_error = error.response.data.errors.password;
            });

            if(res || res.data.login){
                router.visit('/')
            }
        }
    },
}
</script>

<template>
    <section class=" bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] h-screen flex justify-center items-center">
        <div class=" bg-white w-[562px] py-[30px] flex flex-col items-center rounded-md">
            <h1 class=" text-xl font-bold mb-[15px]">Вход</h1>
            <div>
                <input v-model="email" type="email" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light" placeholder="Введите ваш email">
                <p class=" text-red-600 ml-4 font-light" v-if="email_error">{{ email_error[0] }}</p>
            </div>
            <div>
                <input v-model="password" type="password" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light" placeholder="Введите ваш пароль">
                <p class=" text-red-600 ml-4 font-light" v-if="password_error">{{ password_error[0] }}</p>
            </div>
            <button @click.prevent="login()" class=" px-[30px] py-[10px] hover:bg-blue-200 hover:text-blue-600 rounded-md my-[10px] text-lg transition ease-in" type="submit">Войти</button>
            <Link :href="route('user.registration')">У меня нет <span class=" text-blue-600">аккаунта</span></Link>
        </div>
    </section>
</template>