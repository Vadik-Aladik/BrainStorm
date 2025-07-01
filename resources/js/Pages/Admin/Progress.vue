<script>
import ComboComponent from "@/Layout/Combo.vue"
import axios from "axios";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
export default{
    data(){
        return {
            dataCourse: [],
            dataCompleteTest:[],

            modalFlag: false,
            flagLoadCourse: false,

            page: 1,
        }
    },
    components:{
        Link,
        ComboComponent,
    },
    methods:{
        async getData(){
            const res = await axios.post(`/admin/progress/data?page=${this.page}`);
            ++this.page;

            this.dataCourse.push(...res.data.courseWithTests);
            if(res.data.courseTestInfo.next_page_url == null){
                return this.flagLoadCourse = true;
            };
        },
        
        modalTest(indexCourse, indexTest){
            this.dataCompleteTest = this.dataCourse[indexCourse].course_tests[indexTest];
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
        <div class=" py-[30px] px-4 min-[490px]:px-[77px] bg-white rounded-xl min-h-[780px]">
            <h1 class=" text-2xl font-semibold text-center">Прогресс студентов</h1>
            <div v-for="(elem, indexCourse) in dataCourse" :key="elem" class=" mt-5 max-md:flex max-md:flex-col max-md:items-center">
                <h1 class=" font-semibold text-xl">{{elem.course_name}}</h1>
                <div class=" ml-2">
                    <div v-if="elem.course_tests.some(elemCheck => elemCheck.check )" class=" max-md:flex max-md:flex-col max-md:items-center">
                    <!-- <div> -->
                        <span class="text-base my-1 text-gray-500 mt-4">Тесты требующие проверки</span>
                        <div class=" flex flex-wrap w-[186px] min-[450px]:w-[380px] mx-5 md:w-full">
                            <div v-for="(test, indexTest) in elem.course_tests" :key="test">
                                <div v-if="test.check && test">
                                    <div @click.prevent="modalTest(indexCourse, indexTest)" class="truncate group w-[186px] xl:w-[200px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer mr-1 xl:mr-2 mb-[10px] hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                                        <span class="truncate font-semibold text-xl">{{test.test_name}}</span>
                                        <!-- <div class=" text-sm text-end">Проверить тест</div> -->

                                        <div v-if="elem.count_student === 0" class=" text-red-700 font-medium text-sm text-end">Студентов нет</div>
                                        <div v-else-if="elem.count_student === test.test_students.length" class=" text-green-700 font-medium text-sm text-end">Прошли {{ test.test_students.length }} из {{ elem.count_student }}</div>
                                        <div v-else class=" text-sm text-end">Прошли {{ test.test_students.length }} из {{ elem.count_student }}</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="elem.course_tests.some(elemCheck => !elemCheck.check )" class=" max-md:flex max-md:flex-col max-md:items-center">
                        <span class="text-base my-1 text-gray-500 mt-4">Все тесты курса</span>
                        <div class=" flex flex-wrap w-[186px] min-[450px]:w-[380px] mx-5 md:w-full">
                            <div v-for="(test, indexTest) in elem.course_tests" :key="test">
                                <div v-if="!test.check">
                                    <div @click.prevent="modalTest(indexCourse, indexTest)" class="truncate group w-[186px] xl:w-[200px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer mr-1 xl:mr-2 mb-[10px] hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                                        <span class="truncate font-semibold text-xl">{{test.test_name}}</span>
                                        <!-- <div class=" text-sm text-end">Проверить тест</div> -->

                                        <div v-if="elem.count_student === 0" class=" text-red-700 font-medium text-sm text-end">Студентов нет</div>
                                        <div v-else-if="elem.count_student === test.test_students.length" class=" text-green-700 font-medium text-sm text-end">Прошли {{ test.test_students.length }} из {{ elem.count_student }}</div>
                                        <div v-else class=" text-sm text-end">Прошли {{ test.test_students.length }} из {{ elem.count_student }}</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" flex justify-center">
                    <button v-if="!flagLoadCourse" @click.prevent="getData()" class="px-[30px] py-[10px] hover:bg-blue-200 
                hover:text-blue-600 rounded-md my-[10px] text-lg transition ease-in bg-gray-100">Загрузить еще...</button>
                </div>
        </div>
    </ComboComponent>

    <div v-if="modalFlag">
        <div class=" bg-black bg-opacity-50 h-full w-full flex items-center justify-center fixed top-0 left-0 text-lg">
            <div class=" min-w-[400px] max-[490px]:min-w-[320px] max-[490px]:px-4 py-8 px-[77px] bg-white rounded-md">
                <h1 class=" font-bold text-2xl text-blue-600 text-center w-80">{{dataCompleteTest.test_name}}</h1>

                <div class=" mt-5">
                    <p class="text-gray-500 font-semibold text-base mx-auto">Студенты прошедшие тест из курса</p>

                    <div v-if="dataCompleteTest.test_students.length == 0">
                        <div class=" bg-slate-100 rounded-lg flex items-center justify-around px-2 py-2 mt-2 text-sm font-semibold">
                            <div class=" w-8">
                                <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.7275 1.5C18.9866 1.5002 22.4021 4.89789 22.4023 9.04395C22.4023 13.1902 18.9868 16.5887 14.7275 16.5889C10.4681 16.5889 7.05176 13.1903 7.05176 9.04395C7.05199 4.89777 10.4683 1.5 14.7275 1.5Z" stroke="black" stroke-width="3"/>
                                    <path d="M3 22.0713H27C27.8284 22.0713 28.5 22.7429 28.5 23.5713V34.5H1.5V23.5713C1.5 22.7946 2.09028 22.1559 2.84668 22.0791L3 22.0713Z" stroke="black" stroke-width="3"/>
                                    </svg>
                            </div>
                            <div>
                                Никто не прошел тест курса 
                            </div>
                        </div>
                    </div>

                    <div v-else class=" overflow-auto h-[150px] max-w-70 mx-auto">
                        <div v-for="elem in dataCompleteTest.test_students" :key="elem" class=" max-w-70 mx-auto">
                            <Link :href="route('admin.test.check', [dataCompleteTest.test_id, elem.student_id])" class="flex items-center justify-between mt-2">
                                <div class=" flex items-center">
                                    <div class=" w-8">
                                        <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.7275 1.5C18.9866 1.5002 22.4021 4.89789 22.4023 9.04395C22.4023 13.1902 18.9868 16.5887 14.7275 16.5889C10.4681 16.5889 7.05176 13.1903 7.05176 9.04395C7.05199 4.89777 10.4683 1.5 14.7275 1.5Z" stroke="black" stroke-width="3"/>
                                            <path d="M3 22.0713H27C27.8284 22.0713 28.5 22.7429 28.5 23.5713V34.5H1.5V23.5713C1.5 22.7946 2.09028 22.1559 2.84668 22.0791L3 22.0713Z" stroke="black" stroke-width="3"/>
                                            </svg>
                                    </div>
                                    <div class=" ml-10">
                                        <h1 class=" font-semibold text-lg">{{elem.student_name}}</h1>
                                        <p class=" text-sm text-gray-600">{{elem.student_email}}</p>
                                    </div>
                                </div>
                                
                                <!-- <div class=" ml-14"> -->
                                <div class=" text-end">
                                    <div v-if="elem.student_check">
                                        <div v-if="elem.student_score <= 35" class=" font-semibold text-red-600">
                                            {{elem.student_score}}%
                                        </div>

                                        <div v-if="(elem.student_score >= 35 && elem.student_score < 80)" class=" font-semibold text-yellow-600">
                                            {{elem.student_score}}%
                                        </div>

                                        <div v-if="elem.student_score >= 80" class=" font-semibold text-green-700">
                                            {{elem.student_score}}%
                                        </div>
                                    </div>

                                    <div v-if="!elem.student_check" class="font-semibold text-gray-600 ">
                                        Проверка
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <div class=" flex justify-end">
                    <button class=" px-[30px] py-[10px] bg-gray-100 hover:bg-blue-200 hover:text-blue-600 rounded-md mt-5" @click.prevent="modalFlag=!modalFlag">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    
</template>