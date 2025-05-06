<script>
import ComboComponent from "@/Layout/Combo.vue"
import { route } from "ziggy-js";
import { router, Link } from "@inertiajs/vue3";
export default{
    components:{
        ComboComponent,
        Link
    },
    props:[
        'role',
    ],
    methods:{
        async logout(){
            const res = await axios.post('/logout');

            console.log(res);
            if(res || res.data){
                router.visit('/login');
            }
        }
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <div class=" text-2xl flex justify-between">
                <div>Здравствуйте, <span class=" font-semibold text-blue-600">UserName</span></div>
                <div class="flex items-center ">
                    <div v-if="role == 'admin'">
                        <Link :href="route('admin.main')" class="px-[30px] py-[10px] hover:bg-orange-200 hover:text-orange-600 rounded-md my-[10px] text-lg transition ease-in">
                            Admin panel
                        </Link>
                    </div>
                    <button class="ml-14">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.48 0C14.8835 0 14.4 0.483532 14.4 1.08V7.43338C13.9441 7.58866 13.5015 7.77262 13.0742 7.9831L8.58133 3.49018C8.15957 3.06842 7.47575 3.06842 7.05398 3.49018L3.49016 7.054C3.0684 7.47577 3.0684 8.15958 3.49016 8.58135L7.98309 13.0743C7.77261 13.5015 7.58866 13.9441 7.43338 14.4H1.08C0.483532 14.4 0 14.8835 0 15.48V20.52C0 21.1165 0.483531 21.6 1.08 21.6H7.43339C7.58866 22.0559 7.77262 22.4985 7.98309 22.9257L3.49017 27.4187C3.0684 27.8404 3.0684 28.5243 3.49017 28.946L7.05399 32.5098C7.47575 32.9316 8.15957 32.9316 8.58134 32.5098L13.0743 28.0169C13.5015 28.2274 13.9441 28.4113 14.4 28.5666V34.92C14.4 35.5165 14.8835 36 15.48 36H20.52C21.1165 36 21.6 35.5165 21.6 34.92V28.5666C22.0559 28.4113 22.4985 28.2274 22.9257 28.0169L27.4187 32.5098C27.8404 32.9316 28.5242 32.9316 28.946 32.5098L32.5098 28.946C32.9316 28.5243 32.9316 27.8404 32.5098 27.4187L28.0169 22.9258C28.2274 22.4985 28.4113 22.0559 28.5666 21.6H34.92C35.5165 21.6 36 21.1165 36 20.52V15.48C36 14.8835 35.5165 14.4 34.92 14.4H28.5666C28.4113 13.9441 28.2274 13.5015 28.0169 13.0743L32.5098 8.58134C32.9316 8.15958 32.9316 7.47576 32.5098 7.05399L28.946 3.49017C28.5242 3.06841 27.8404 3.06841 27.4187 3.49017L22.9257 7.98309C22.4985 7.77262 22.0559 7.58866 21.6 7.43338V1.08C21.6 0.483532 21.1165 0 20.52 0H15.48ZM18 24.12C21.38 24.12 24.12 21.38 24.12 18C24.12 14.62 21.38 11.88 18 11.88C14.62 11.88 11.88 14.62 11.88 18C11.88 21.38 14.62 24.12 18 24.12Z" fill="black"/>
                        </svg>
                    </button>

                    <button class=" ml-14" @click.prevent="logout">
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3 3H17V6.32451H20V3C20 1.34315 18.6569 0 17 0H3C1.34315 0 0 1.34315 0 3V23C0 24.6569 1.34315 26 3 26H17C18.6569 26 20 24.6569 20 23V19.8164H17V23H3L3 3Z" fill="black"/>
                        <path d="M25.5258 13.6052C25.8211 13.3148 25.8251 12.8399 25.5347 12.5446L20.8018 7.73226C20.5114 7.43695 20.0366 7.43302 19.7412 7.72347C19.4459 8.01393 19.4419 8.48879 19.7323 8.7841L23.9393 13.0617L19.6613 17.2691C19.3659 17.5595 19.362 18.0344 19.6524 18.3297C19.9428 18.625 20.4177 18.6289 20.713 18.3385L25.5258 13.6052ZM7.99372 13.6797L24.9936 13.8205L25.0062 12.3205L8.00628 12.1797L7.99372 13.6797Z" fill="black"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </ComboComponent>
</template>