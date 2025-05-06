<script>
import { route } from 'ziggy-js';
import { router, Link } from '@inertiajs/vue3';

export default{
    data(){
        return{
            test: [],

            correct_answer: true,
            wrong_answer: false,
        }
    },
    components:{
        Link
    },
    props:[
        "id_course",
        "id_test",
        "user_test",
    ],
    methods:{
        async testGet(){
            const res = await axios.post(`/test/${this.id_test}/get`);
            this.test = res.data.test[0];
            console.log(res);
        },

        checkAnswers(id_answer){
            return this.user_test[0].answer_get.some(element => element.answer_id === id_answer);
        },
    },
    mounted(){
        this.testGet();
    }
}
</script>

<template>
    <div class=" py-[30px] bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] min-h-screen flex justify-center items-center text-lg">
        <div class=" container mx-auto">
            <div class=" bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px] max-sm:px-5 max-sm:text-xs">
                <h1 class=" text-2xl text-blue-600 font-semibold mb-[30px]">{{test.test_name}}</h1>

                <div v-for="(quest, key) in test.quest" :key="key" class=" flex mb-5">
                    <span class=" font-semibold text-blue-600">{{ key+1 }}.</span>
                    <div class="ml-2">
                        <div class="font-semibold">{{quest.quest_text}}</div>
                        <div v-for="(questAnswer, keyAnswer) in quest.answer" :key="keyAnswer">
                            <div v-if="questAnswer.type == 'radio'">
                                <div class=" flex items-center mb-1"> 
                                    <input type="radio" :name="'text'+key" :value="questAnswer.status" id="" disabled :checked="checkAnswers(questAnswer.id)">
                                    <label class=" ml-2" for="">{{ questAnswer.answer_text }}</label>
                                </div>
                            </div>

                            <div v-if="questAnswer.type == 'checkbox'">
                                <div class=" flex items-center mb-1">
                                    <input type="checkbox" :name="'text'+key" :value="questAnswer.status" id="" disabled :checked="checkAnswers(questAnswer.id)">
                                    <label class=" ml-2" for="">{{ questAnswer.answer_text }}</label>
                                </div>
                            </div>

                            <div v-if="questAnswer.type == 'input'" class=" flex">
                                <input :value="this.user_test[0].answer_get.find((elem)=>elem.answer_id==questAnswer.id)?.answer" type="text" class=" mt-1 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[363px] pl-[10px] max-sm:w-[250px]" :name="'text'+key" id="" placeholder="Введите ответ" disabled>
                                <div>
                                    <button class="px-[30px] py-[10px] hover:bg-green-200 hover:text-green-600 rounded-md">Верно</button>
                                    <button class="px-[30px] py-[10px] hover:bg-red-200 hover:text-red-600 rounded-md">Не верно</button>
                                </div>
                            </div>

                            <div v-if="questAnswer.type == 'textarea'" class=" flex">
                                <textarea class=" mt-1 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[150px] w-[363px] pl-[10px] resize-none max-sm:w-[250px]" :name="'text'+key" id="" disabled placeholder="Введите ответ">{{ this.user_test[0].answer_get.find((elem)=>elem.answer_id==questAnswer.id)?.answer }}</textarea>
                                <div>
                                    <button class="px-[30px] py-[10px] hover:bg-green-200 hover:text-green-600 rounded-md">Верно</button>
                                    <button class="px-[30px] py-[10px] hover:bg-red-200 hover:text-red-600 rounded-md">Не верно</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" flex justify-end">
                    <Link :href="route('student.index')" class=" py-[6px] px-[20px] rounded hover:bg-blue-200 hover:text-blue-600 transition ease-in ml-5 text-lg">Закрыть</Link>
                </div>
            </div>
        </div>
    </div>
</template>