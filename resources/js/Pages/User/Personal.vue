<script>
import ComboComponent from "@/Layout/Combo.vue"
import { route } from "ziggy-js";
import { router, Link } from "@inertiajs/vue3";
export default{
    data(){
        return{
            user: [],
            result: [],

            modalFlag:false,
            modalDeleteFlag:false,

            name:'',
            email:'',
            password:'',

            passwordDelete:'',
        }
    },
    components:{
        ComboComponent,
        Link
    },
    methods:{
        async logout(){
            const res = await axios.post('/logout');

            console.log(res);
            if(res || res.data){
                router.visit('/login');
            }
        },
        async personalData(){
            const res = await axios.post('/personal');

            this.user = res.data.user[0];
            this.result = res.data.result;
        },
        async changePersonal(){
            const res = await axios.post('/personal/change', {
                name: this.user.name,
                email: this.user.email,
                password: this.password,
            }).catch((error)=>{
                console.log(error);
            })

            console.log(res);
        },
        async deleteAccountPost(){
            const res = await axios.post('/deleteAcc', {
                password: this.passwordDelete
            }).catch((error)=>{
                console.log(error);
            })

            if(res && res.data.delete_account){
                router.visit('/login');
            }

            console.log(res);
        },

        deleteAcc(){
            this.modalFlag = !this.modalFlag;
            this.modalDeleteFlag = !this.modalDeleteFlag;
        }
    },
    mounted(){
        this.personalData();
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <!-- <div class=" text-2xl flex justify-between max-[550px]:block"> -->
            <div class=" text-2xl flex justify-between items-center max-md:block max-[766px]:flex max-[766px]:flex-col max-[766px]:items-center">
                <div>Здравствуйте, <span class=" font-semibold text-blue-600">{{user.name}}</span></div>
                <!-- <div class="flex items-center max-md:flex-col max-md:h-36 max-md:justify-between max-[550px]:h-[40px] max-[550px]:my-4 max-[550px]:flex-row max-[460px]:flex-col max-[460px]:h-36 max-[460px]:mt-5"> -->
                <div class="flex items-center my-4 max-[489px]:flex-col max-[489px]:max-h-28 max-[489px]:justify-between">
                    <div v-if="user.role == 'admin'" class="max-[489px]:mb-4">
                        <Link :href="route('admin.main')" class="px-[30px] py-[10px] bg-gray-100 hover:bg-orange-200 hover:text-orange-600 rounded-md my-[10px] text-lg transition ease-in">
                            Admin panel
                        </Link>
                    </div>
                    <div class=" w-44 flex justify-around">
                        <button @click.prevent="modalFlag = !modalFlag" class=" max-[460px]:ml-0 group transition-all ease-in">
                            <div class="group-hover:hidden p-2 transition-all ease-in">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.48 0C14.8835 0 14.4 0.483532 14.4 1.08V7.43338C13.9441 7.58866 13.5015 7.77262 13.0742 7.9831L8.58133 3.49018C8.15957 3.06842 7.47575 3.06842 7.05398 3.49018L3.49016 7.054C3.0684 7.47577 3.0684 8.15958 3.49016 8.58135L7.98309 13.0743C7.77261 13.5015 7.58866 13.9441 7.43338 14.4H1.08C0.483532 14.4 0 14.8835 0 15.48V20.52C0 21.1165 0.483531 21.6 1.08 21.6H7.43339C7.58866 22.0559 7.77262 22.4985 7.98309 22.9257L3.49017 27.4187C3.0684 27.8404 3.0684 28.5243 3.49017 28.946L7.05399 32.5098C7.47575 32.9316 8.15957 32.9316 8.58134 32.5098L13.0743 28.0169C13.5015 28.2274 13.9441 28.4113 14.4 28.5666V34.92C14.4 35.5165 14.8835 36 15.48 36H20.52C21.1165 36 21.6 35.5165 21.6 34.92V28.5666C22.0559 28.4113 22.4985 28.2274 22.9257 28.0169L27.4187 32.5098C27.8404 32.9316 28.5242 32.9316 28.946 32.5098L32.5098 28.946C32.9316 28.5243 32.9316 27.8404 32.5098 27.4187L28.0169 22.9258C28.2274 22.4985 28.4113 22.0559 28.5666 21.6H34.92C35.5165 21.6 36 21.1165 36 20.52V15.48C36 14.8835 35.5165 14.4 34.92 14.4H28.5666C28.4113 13.9441 28.2274 13.5015 28.0169 13.0743L32.5098 8.58134C32.9316 8.15958 32.9316 7.47576 32.5098 7.05399L28.946 3.49017C28.5242 3.06841 27.8404 3.06841 27.4187 3.49017L22.9257 7.98309C22.4985 7.77262 22.0559 7.58866 21.6 7.43338V1.08C21.6 0.483532 21.1165 0 20.52 0H15.48ZM18 24.12C21.38 24.12 24.12 21.38 24.12 18C24.12 14.62 21.38 11.88 18 11.88C14.62 11.88 11.88 14.62 11.88 18C11.88 21.38 14.62 24.12 18 24.12Z" fill="black"/>
                                </svg>
                            </div>
                            <div class="group-hover:block group-hover:bg-blue-200 hidden p-2 rounded-md transition-all ease-in">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.48 0C14.8835 0 14.4 0.483532 14.4 1.08V7.43338C13.9441 7.58866 13.5015 7.77262 13.0742 7.9831L8.58133 3.49018C8.15957 3.06842 7.47575 3.06842 7.05398 3.49018L3.49016 7.054C3.0684 7.47577 3.0684 8.15958 3.49016 8.58135L7.98309 13.0743C7.77261 13.5015 7.58866 13.9441 7.43338 14.4H1.08C0.483532 14.4 0 14.8835 0 15.48V20.52C0 21.1165 0.483531 21.6 1.08 21.6H7.43339C7.58866 22.0559 7.77262 22.4985 7.98309 22.9257L3.49017 27.4187C3.0684 27.8404 3.0684 28.5243 3.49017 28.946L7.05399 32.5098C7.47575 32.9316 8.15957 32.9316 8.58134 32.5098L13.0743 28.0169C13.5015 28.2274 13.9441 28.4113 14.4 28.5666V34.92C14.4 35.5165 14.8835 36 15.48 36H20.52C21.1165 36 21.6 35.5165 21.6 34.92V28.5666C22.0559 28.4113 22.4985 28.2274 22.9257 28.0169L27.4187 32.5098C27.8404 32.9316 28.5242 32.9316 28.946 32.5098L32.5098 28.946C32.9316 28.5243 32.9316 27.8404 32.5098 27.4187L28.0169 22.9258C28.2274 22.4985 28.4113 22.0559 28.5666 21.6H34.92C35.5165 21.6 36 21.1165 36 20.52V15.48C36 14.8835 35.5165 14.4 34.92 14.4H28.5666C28.4113 13.9441 28.2274 13.5015 28.0169 13.0743L32.5098 8.58134C32.9316 8.15958 32.9316 7.47576 32.5098 7.05399L28.946 3.49017C28.5242 3.06841 27.8404 3.06841 27.4187 3.49017L22.9257 7.98309C22.4985 7.77262 22.0559 7.58866 21.6 7.43338V1.08C21.6 0.483532 21.1165 0 20.52 0H15.48ZM18 24.12C21.38 24.12 24.12 21.38 24.12 18C24.12 14.62 21.38 11.88 18 11.88C14.62 11.88 11.88 14.62 11.88 18C11.88 21.38 14.62 24.12 18 24.12Z" fill="#2563eb"/>
                                </svg>
                            </div>
                        </button>

                        <button class=" max-[460px]:ml-0 group " @click.prevent="logout">
                            <div class="group-hover:hidden p-2">
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 3H17V6.32451H20V3C20 1.34315 18.6569 0 17 0H3C1.34315 0 0 1.34315 0 3V23C0 24.6569 1.34315 26 3 26H17C18.6569 26 20 24.6569 20 23V19.8164H17V23H3L3 3Z" fill="black"/>
                                    <path d="M25.5258 13.6052C25.8211 13.3148 25.8251 12.8399 25.5347 12.5446L20.8018 7.73226C20.5114 7.43695 20.0366 7.43302 19.7412 7.72347C19.4459 8.01393 19.4419 8.48879 19.7323 8.7841L23.9393 13.0617L19.6613 17.2691C19.3659 17.5595 19.362 18.0344 19.6524 18.3297C19.9428 18.625 20.4177 18.6289 20.713 18.3385L25.5258 13.6052ZM7.99372 13.6797L24.9936 13.8205L25.0062 12.3205L8.00628 12.1797L7.99372 13.6797Z" fill="black"/>
                                </svg>
                            </div>
                            <div class="group-hover:block group-hover:bg-red-200 hidden p-2 rounded-md">
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 3H17V6.32451H20V3C20 1.34315 18.6569 0 17 0H3C1.34315 0 0 1.34315 0 3V23C0 24.6569 1.34315 26 3 26H17C18.6569 26 20 24.6569 20 23V19.8164H17V23H3L3 3Z" fill="#b91c1c"/>
                                    <path d="M25.5258 13.6052C25.8211 13.3148 25.8251 12.8399 25.5347 12.5446L20.8018 7.73226C20.5114 7.43695 20.0366 7.43302 19.7412 7.72347C19.4459 8.01393 19.4419 8.48879 19.7323 8.7841L23.9393 13.0617L19.6613 17.2691C19.3659 17.5595 19.362 18.0344 19.6524 18.3297C19.9428 18.625 20.4177 18.6289 20.713 18.3385L25.5258 13.6052ZM7.99372 13.6797L24.9936 13.8205L25.0062 12.3205L8.00628 12.1797L7.99372 13.6797Z" fill="#b91c1c"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center min-[768px]:block">
                <span class="text-base my-1 text-gray-500">Ваш прогресс</span>
                <!-- <div v-if="result.data.length != 0">    -->
                <div v-if="result.length != 0">   
                    <div class="flex flex-wrap mx-5 mt-3 justify-center lg:justify-normal">
                        <!-- <div v-for="elem in result.data" :key="elem"> -->
                        <div v-for="elem in result" :key="elem">
                            <Link :href="route('student.test.view', elem.test_id)">
                                <div v-if="elem.status && elem.score >= 80" class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] 
                                    hover:border-green-700 hover:bg-green-200 hover:text-green-700 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer truncate">
                                    <span class="text-ellipsis overflow-hidden font-semibold text-xl">{{elem.name_test}}</span>
                                    <p class=" text-sm truncate">{{elem.name_course}}</p>
                                    <div class="font-semibold text-xl text-green-700 text-end">{{elem.score}}%</div>
                                </div>
                            </Link>
                            
                            <Link :href="route('student.test.view', elem.test_id)">
                                <div v-if="elem.status && (elem.score >= 35 && elem.score < 80)" class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] 
                                    hover:border-yellow-600 hover:bg-yellow-200 hover:text-yellow-600 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer truncate">
                                    <span class="text-ellipsis overflow-hidden font-semibold text-xl">{{elem.name_test}}</span>
                                    <p class=" text-sm truncate">{{elem.name_course}}</p>
                                    <div class="font-semibold text-xl text-yellow-600 text-end">{{elem.score}}%</div>
                                </div>
                            </Link>
                            
                            <Link :href="route('student.test.view', elem.test_id)">
                                <div v-if="elem.status && elem.score <= 35" class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] 
                                hover:border-red-700 hover:bg-red-200 hover:text-red-700 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer truncate">
                                    <span class="text-ellipsis overflow-hidden font-semibold text-xl">{{elem.name_test}}</span>
                                    <p class=" text-sm truncate">{{elem.name_course}}</p>
                                    <div class="font-semibold text-xl text-red-700 text-end">{{elem.score}}%</div>
                                </div>
                            </Link>
                        </div>

                        <Link :href="route('student.progress')" class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] hover:border-blue-600 hover:bg-blue-200 hover:text-blue-600 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer group truncate" v-if="result.length >= 11">
                            <div class="font-semibold text-lg truncate text-wrap">Загрузить еще</div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>

    <div v-if="modalFlag">
        <div class=" bg-black bg-opacity-50 h-full w-full flex items-center justify-center fixed top-0 left-0 text-lg">
            <!-- <div class=" min-w-[400px] py-8 px-[77px] bg-white rounded-md"> -->
            <div class=" min-w-[400px] max-[490px]:min-w-[320px] max-[490px]:px-4 py-8 px-[77px] bg-white rounded-md">
                <div class=" flex justify-between items-center">
                    <span class=" text-xl">Редактирование аккаунта</span>
                    <button @click.prevent="deleteAcc()" class="group">
                        <div class=" group-hover:hidden p-2">
                            <svg width="23" height="28" viewBox="0 0 23 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.2906" cy="7.03448" r="5.53448" stroke="black" stroke-width="3"/>
                            <path d="M5 17.5H18C19.933 17.5 21.5 19.067 21.5 21V26.5H1.5V21C1.5 19.067 3.067 17.5 5 17.5Z" stroke="black" stroke-width="3"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6749 23.3498C18.809 23.3498 21.3498 20.809 21.3498 17.6749C21.3498 14.5407 18.809 12 15.6749 12C12.5407 12 10 14.5407 10 17.6749C10 20.809 12.5407 23.3498 15.6749 23.3498ZM19.695 16.8475H11.5373V18.7391H19.695V16.8475Z" fill="#CD0000"/>
                            </svg>
                        </div>
                        <div class="group-hover:block group-hover:bg-red-700 hidden p-2 rounded-md">
                            <svg width="23" height="28" viewBox="0 0 23 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.2906" cy="7.03448" r="5.53448" stroke="white" stroke-width="3"/>
                            <path d="M5 17.5H18C19.933 17.5 21.5 19.067 21.5 21V26.5H1.5V21C1.5 19.067 3.067 17.5 5 17.5Z" stroke="white" stroke-width="3"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6749 23.3498C18.809 23.3498 21.3498 20.809 21.3498 17.6749C21.3498 14.5407 18.809 12 15.6749 12C12.5407 12 10 14.5407 10 17.6749C10 20.809 12.5407 23.3498 15.6749 23.3498ZM19.695 16.8475H11.5373V18.7391H19.695V16.8475Z" fill="#ca8a04"/>
                            </svg>
                        </div>
                    </button>
                </div>

                <div class="flex flex-col mt-8">
                    <input v-model="user.name" placeholder="Введите новое имя" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light max-[490px]:w-72 max-[490px]:items-center" type="text">
                    <input v-model="user.email" placeholder="Введите новую почту" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light max-[490px]:w-72 max-[490px]:items-center" type="email">
                    <input v-model="password" placeholder="Введите новый пароль" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light max-[490px]:w-72 max-[490px]:items-center" type="password">
                </div>

                <div class=" flex flex-col items-center mt-5">
                    <button @click.prevent="changePersonal" class="px-[30px] py-[10px] bg-gray-100  hover:bg-green-200 hover:text-green-600 rounded-md transition easy-in mb-3">Сохранить изменения</button>
                    <button @click.prevent="modalFlag = !modalFlag" class="px-[30px] py-[10px] bg-gray-100  hover:bg-blue-200 hover:text-blue-600 rounded-md transition easy-in mb-3">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="modalDeleteFlag">
        <div class=" bg-black bg-opacity-50 h-full w-full flex items-center justify-center fixed top-0 left-0 text-lg">
            <div class=" min-w-[400px] max-[490px]:min-w-[320px] max-[490px]:px-4 py-8 px-[77px] bg-white rounded-md flex flex-col items-center">
                <h1 class=" font-semibold text-red-600 text-xl text-center">Удаление аккаунта</h1>
                <p>Введите пароль, чтобы завершить процесс удаления аккаунта</p>
                <input v-model="passwordDelete" type="password" class=" w-[370px] h-[30px] px-[10px] focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded mt-[5px] placeholder:font-light max-[490px]:w-72 max-[490px]:items-center" placeholder="Введите ваш пароль">
                <div class=" flex flex-col items-center mt-5">
                    <button @click.prevent="deleteAcc()" class="px-[30px] py-[10px] bg-gray-100  hover:bg-green-200 hover:text-green-600 rounded-md transition easy-in mb-3">Вернуться</button>
                    <button @click.prevent="deleteAccountPost()" class="px-[30px] py-[10px] bg-gray-100  hover:bg-red-200 hover:text-red-600 rounded-md transition easy-in mb-3">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</template>