<script>
import { route } from 'ziggy-js';
import { router, Link } from '@inertiajs/vue3';
import axios from 'axios';

export default{
    data(){
        return{
            test: [],
            userInput: {},

            count:0,
        }
    },
    components:{
        Link
    },
    props:[
        "id_course",
        "id_test",
        "user_test",
        "user_id",
    ],
    methods:{
        async testGet(){
            const res = await axios.post(`/test/${this.id_test}/get`);
            this.test = res.data.test[0];
        },
        async postRes(){
            this.reCheckTest();

            const score = Math.round((this.count/this.test.quest.length)*100);
            
            const res = await axios.post('/admin/changeResult', {
                    user_id: this.user_id,
                    test_id: this.id_test,
                    answer_user: this.userInput,
                    score: score
                });
            if(res || res.data){
                router.visit('/admin/progress');
            }
        },

        checkAnswers(id_answer){
            return this.user_test[0].answer_get.some(element => element.answer_id === id_answer);
        },
        reCheckTest(){
            this.count = 0;
            this.test.quest.forEach((element, index) => {
                if(element.answer[0].type == 'checkbox'){
                    const allAnswers = element.answer.filter(item => item.type == 'checkbox').map(id => id.id); // все варианты ответы
                    const userSelectedAnswers = this.user_test[0].answer_get.map(id => id.answer_id).filter(elem => allAnswers.includes(elem)); // выбранные ответы пользователем
                    const correctAnswers = element.answer.filter(item => item.status).map(id => id.id) // конкретно правильные ответы

                    const allCorrectSelected = correctAnswers.every(ca => userSelectedAnswers.includes(ca)); // true (все правильные выбраны)
                    const noIncorrectSelected = userSelectedAnswers.every(ua => correctAnswers.includes(ua));
                    
                    if(allCorrectSelected && noIncorrectSelected){
                        this.count++;
                    }
                }
                else if(element.answer[0].type == 'radio'){
                    const trueAnswer = element.answer.find(elem=> elem.status);
                    const result = this.user_test[0].answer_get.some(elem => elem.answer_id == trueAnswer.id);

                    if(result){
                        this.count++;
                    }
                }
                else if(element.answer[0].type == 'input' || element.answer[0].type == 'textarea'){
                    if(element.answer[0].status === null || element.answer[0].status === undefined){
                        element.answer[0].status = false;
                    }
                    else if(element.answer[0].status){
                        this.count++;
                    }
                    this.userInput[index] = element.answer[0];
                }
            });
        },

        changeStatusAnswer(index, isCorrect){
            this.test.quest[index].answer[0].status = isCorrect;
        }
    },
    mounted(){
        this.testGet();
    }
}
</script>

<template>
    <div class=" py-[30px] bg-cover bg-center bg-[url(/public/img/background/page-course.svg)] min-h-screen flex justify-center items-center text-lg">
        <div class=" container mx-auto">
            <!-- <div class=" bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px] "> -->
            <div class="bg-white py-[30px] px-[76px] rounded-[10px] min-h-[100px] max-sm:px-5 my-5 max-md:flex max-md:flex-col max-md:items-center">
            <!-- <div class="bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px] max-sm:px-5 my-5 max-md:flex max-md:flex-col max-md:items-center"> -->
                <div>
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

                                <div v-if="questAnswer.type == 'input'" class=" flex max-lg:flex-col max-lg:mt-2">
                                    <input :value="this.user_test[0].answer_get.find((elem)=>elem.answer_id==questAnswer.id)?.answer" type="text" class=" mt-1 focus:outline-none focus:border-2 
                                    focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[363px] pl-[10px] max-sm:w-[250px]" :name="'text'+key" id="" placeholder="Введите ответ" disabled>
                                    <div class=" max-[350px]:flex-col max-[350px]:mt-2">
                                        <button @click.prevent="changeStatusAnswer(key, true)" class="ml-4 max-[350px]:ml-0 px-[30px] py-[5px] hover:bg-green-200 hover:text-green-600 rounded-md">Верно</button>
                                        <button @click.prevent="changeStatusAnswer(key, false)" class="ml-4 max-[350px]:ml-0 px-[30px] py-[5px] hover:bg-red-200 hover:text-red-600 rounded-md">Не верно</button>
                                    </div>
                                </div>

                                <div v-if="questAnswer.type == 'textarea'" class=" flex max-lg:flex-col max-lg:mt-2">
                                    <textarea class=" mt-1 focus:outline-none focus:border-2 
                                    focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[150px] w-[363px] pl-[10px] resize-none max-sm:w-[250px]" 
                                    :name="'text'+key" id="" disabled placeholder="Введите ответ">{{ this.user_test[0].answer_get.find((elem)=>elem.answer_id==questAnswer.id)?.answer }}</textarea>
                                    <div class=" max-[350px]:flex-col max-[350px]:mt-2">
                                        <button @click.prevent="changeStatusAnswer(key, true)" class="ml-4 max-[350px]:ml-0 px-[30px] py-[5px] hover:bg-green-200 hover:text-green-600 rounded-md">Верно</button>
                                        <button @click.prevent="changeStatusAnswer(key, false)" class="ml-4 max-[350px]:ml-0 px-[30px] py-[5px] hover:bg-red-200 hover:text-red-600 rounded-md">Не верно</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class=" flex justify-between max-md:flex-col ">
                    <Link :href="route('admin.progress')" class=" py-[6px] px-[20px] rounded hover:bg-blue-200 text-center hover:text-blue-600 transition ease-in ml-5 text-lg max-md:my-5 max-md:ml-0">Закрыть</Link>
                    <!-- <Link :href="route('admin.progress')" class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in ml-5 text-lg">Сохранить</Link> -->
                    <button @click.prevent="postRes" class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in ml-5 text-lg max-md:ml-0">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

