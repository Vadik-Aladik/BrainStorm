<script>
import ComboComponent from "@/Layout/Combo.vue"
import axios from "axios";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
export default{
    data(){
        return {
            infoTest:[],
            infoCourse:[],
            dataCourse:[],

            modalFlag: false,
        }
    },
    components:{
        Link,
        ComboComponent,
    },
    methods:{
        async getData(){
            const res = await axios.post('/admin/progress/data');
            this.infoTest = res.data.course_for_check;
            this.infoCourse = res.data.courseWithTests;
            console.log(res, this.infoTest);
        },
        modalTestCheck(index){
            this.dataCourse=[];
            this.dataCourse = this.infoTest[index];

            return this.modalFlag = !this.modalFlag;
        }
    },

    mounted(){
        this.getData();
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <h1 class=" text-2xl font-semibold text-center">Прогресс студентов</h1>
            <div>
                <span class=" font-semibold text-xl mb-3">Проверить</span>
                <div class=" flex flex-wrap mx-5 mt-3">
                    <div v-for="(elem, index) in infoTest" :key="index">
                        <div class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] hover:border-blue-600 hover:bg-blue-200 hover:text-blue-600 transition ease-in mr-[23px] mb-[10px] cursor-pointer flex flex-col justify-between" @click.prevent="modalTestCheck(index)">
                            <span class="truncate font-semibold text-xl">{{elem.test_name}}</span>
                            <div class=" text-sm text-end">Проверить</div>
                        </div>
                    </div>
                    <!-- <div class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] hover:border-blue-600 hover:bg-blue-200 hover:text-blue-600 transition ease-in mr-[23px] mb-[10px] cursor-pointer flex flex-col justify-between">
                        <span class=" font-semibold text-xl">Name Course</span>
                        <div class=" text-sm text-end">Проверить</div>
                    </div> -->
                </div>
            </div>
        </div>
    </ComboComponent>

    <div v-if="modalFlag">
        <div class=" bg-black bg-opacity-50 h-full w-full flex items-center justify-center fixed top-0 left-0 text-lg">
            <div class=" min-w-[400px] py-8 px-4 bg-white rounded-md">
                <h1 class=" font-bold text-2xl text-blue-600 text-center">{{dataCourse.test_name}}</h1>

                <div class=" mt-5">
                    <p class="text-gray-500 font-semibold text-base">Студенты прошедшие курс</p>

                    <div v-for="elem in dataCourse.test_student" :key="elem">
                        <Link :href="route('admin.test.check', [dataCourse.id, elem.user_get.id])" class="flex items-center justify-between mt-2">
                            <div class=" flex items-center">
                                <div class=" w-8">
                                    <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.7275 1.5C18.9866 1.5002 22.4021 4.89789 22.4023 9.04395C22.4023 13.1902 18.9868 16.5887 14.7275 16.5889C10.4681 16.5889 7.05176 13.1903 7.05176 9.04395C7.05199 4.89777 10.4683 1.5 14.7275 1.5Z" stroke="black" stroke-width="3"/>
                                        <path d="M3 22.0713H27C27.8284 22.0713 28.5 22.7429 28.5 23.5713V34.5H1.5V23.5713C1.5 22.7946 2.09028 22.1559 2.84668 22.0791L3 22.0713Z" stroke="black" stroke-width="3"/>
                                        </svg>
                                </div>
                                <div class=" ml-10">
                                    <h1 class=" font-semibold text-lg">{{elem.user_get.name}}</h1>
                                    <p class=" text-sm text-gray-600">{{elem.user_get.email}}</p>
                                </div>
                            </div>
                            
                            <div class=" ml-14">
                                <div v-if="elem.is_checked">
                                    <div v-if="elem.score_student <= 35" class=" font-semibold text-red-600">
                                        {{elem.score_student}}%
                                    </div>

                                    <div v-if="(elem.score_student >= 35 && elem.score_student < 80)" class=" font-semibold text-yellow-600">
                                        {{elem.score_student}}%
                                    </div>

                                    <div v-if="elem.score_student >= 80" class=" font-semibold text-green-700">
                                        {{elem.score_student}}%
                                    </div>
                                </div>

                                <div v-if="!elem.is_checked" class="font-semibold text-gray-600 ">
                                    Проверить работу
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <div class=" flex justify-end">
                    <button class=" px-[30px] py-[10px] hover:bg-blue-200 hover:text-blue-600 rounded-md mt-8" @click.prevent="modalFlag=!modalFlag">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>