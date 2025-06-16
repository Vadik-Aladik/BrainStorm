<script>
import ComboComponent from "@/Layout/Combo.vue"
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

export default{
    components:{
        Link,
        ComboComponent
    },
    props:[
        'courses',
        'result',
        'check',
        'res',
        // 'test_progress',
        // 'course_user',
    ],
    mounted(){
        // console.log(this.courses);
        console.log(this.res);
        // console.log(this.test_progress);
        // console.log(this.course_user);
    }
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]">
            <div class=" flex flex-col items-center min-[768px]:block">
                <span class=" font-semibold text-xl">Ваши курсы</span>
                <div class=" flex flex-col items-center min-[768px]:block">
                    <div v-if="courses.data <= 0" class="flex flex-wrap mx-5 mt-3">
                        <div class=" w-[173px] h-[173px] rounded-xl bg-gray-400 flex flex-col justify-center items-center cursor-pointer mr-4">
                            <img src="/public/img/logo/Course.svg" alt="course_create">
                            <h1 class=" font-semibold text-xl text-center text-white mt-3">Здесь хранятся ваши курсы</h1>
                        </div>
                    </div>
                    <div v-else class="flex flex-wrap mx-5 mt-3 w-[173px] min-[400px]:w-[378px] md:w-full">
                        <div v-for="elem in courses.data" :key="elem">
                            <Link :href="route('student.course', elem.course_id)">
                                <div class=" w-[173px] h-[173px] rounded-xl mr-4 p-4 flex flex-col justify-end mb-2 max-[399px]:mr-0"
                                    :style="{
                                        backgroundImage: `linear-gradient(to top, ${elem.course_color}, transparent), url(${elem.course_img})`,
                                        backgroundSize: 'cover', 
                                        backgroundPosition: 'center', 
                                        backgroundRepeat: 'no-repeat'
                                    }">
                                    <div class=" flex flex-col items-end text-white">
                                        <h1 class=" font-semibold text-lg text-center mt-3 w-[100%]"> {{ elem.course_name }}</h1>
                                        <p class=" text-sm mt-[10px]">Открыть>></p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class=" mt-8 flex flex-col items-center min-[768px]:block">
                <span class=" font-semibold text-xl">Ваши результы</span>
                    <div v-if="result.data.length > 0" class="flex flex-col items-center min-[768px]:block">
                        <span class="text-base my-1 text-gray-500">Проверенные</span>
                        <div class="flex flex-wrap mx-5 mt-3 justify-center lg:justify-normal">
                            <div v-for="elem in result.data" :key="elem">
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
                        </div>
                    </div>
                
                <div v-if="result.data.length > 0">
                    <div class=" mt-3 flex flex-col items-center min-[768px]:block">
                        <span class="text-base my-1 text-gray-500">На проверке</span>
                        <div class="flex flex-wrap mx-5 mt-3 justify-center lg:justify-normal">
                            <div v-for="elem in check.data" :key="elem">
                                <div v-if="elem.status==false" class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] 
                                hover:border-blue-600 hover:bg-blue-200 hover:text-blue-600 transition ease-in mr-1 xl:mr-[16px] mb-[10px] cursor-pointer group truncate">
                                    <span class="text-ellipsis overflow-hidden font-semibold text-xl">{{elem.name_test}}</span>
                                    <p class=" text-sm truncate">{{elem.name_course}}</p>
                                    <div class="font-semibold text-xl text-gray-500 text-end group-hover:text-blue-600">На проверке</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="result.data.length == 0">
                    <div class="w-[200px] min-h-[85px] rounded-md border-2 border-gray-400 p-[10px] mr-1 xl:mr-[16px] mb-[10px] cursor-pointer truncate mx-5 mt-3">
                        <span class="text-ellipsis overflow-hidden font-semibold text-xl">Ваши тесты</span>
                        <p class=" text-sm truncate"></p>
                        <div class="font-semibold text-xl text-gray-500 text-end">Ваши результаты</div>
                    </div>
                </div>
            </div>
            
        </div>
    </ComboComponent>
</template>