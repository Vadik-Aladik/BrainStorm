<script>
import ComboComponent from "@/Layout/Combo.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
export default{
    components:{
        Link,
        ComboComponent,
    },
    props:[
        'course_admin',
        'complete_test',
    ], 
}
</script>

<template>
    <ComboComponent>
        <div class=" py-[30px] px-4 min-[490px]:px-[77px] bg-white rounded-xl min-h-[780px]">
            <h1 class=" font-semibold text-2xl text-center">{{course_admin[0].course_name}}</h1>
            <div class=" min-h-[678px] mt-5">

                <div v-if="course_admin[0].all_test.length != 0" class=" mt-3 flex flex-col items-center md:items-start min-[450px]:items-normal">
                    <h1 class=" md:text-xl md:font-semibold mb-2 mx-auto md:mx-0">Не пройденные тесты</h1>
                    <div class="flex flex-wrap w-[186px] min-[450px]:w-[380px] mx-5 md:w-full">
                        <div v-for="elem in course_admin[0].all_test" :key="elem">
                            <Link :href="route('student.test', [course_admin[0].id, elem.id])">
                                <div class="truncate group w-[186px] xl:w-[208px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer mr-1 xl:mr-2 mb-[10px] hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                                    <h1 class="font-semibold text-lg truncate ">{{ elem.test_name }}</h1>
                                    <p class=" text-green-600 group-hover:text-blue-600">Пройти тест</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-if="course_admin[0].complete_test.length != 0" class=" mt-3 flex flex-col items-center md:items-start min-[450px]:items-normal">
                    <h1 class=" md:text-xl md:font-semibold mb-2 mx-auto md:mx-0">Пройденные тесты</h1>
                    <div class="flex flex-wrap w-[186px] min-[450px]:w-[380px] mx-5 md:w-full">
                        <div v-for="elem in course_admin[0].complete_test" :key="elem">
                            <div class="truncate group w-[186px] xl:w-[208px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer mr-1 xl:mr-2 mb-[10px] hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                                <h1 class="font-semibold text-lg truncate ">{{ elem.test_name }}</h1>
                                <p class=" text-red-600 group-hover:text-blue-600">Тест пройден</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>
</template>