<script>
import axios from 'axios';

export default{
    data(){
        return{
            // answers: [
            //     {quest_id: 1, type:'radio', answers_id:0, status: 1},
            //     {quest_id: 2, type:'checkbox', answers_id:[0,2,5]},
            //     {quest_id: 3, type:'input', user_answers: "", correct_answer:""},
            //     {quest_id: 4, type:'textarea', user_answers: ""},
            // ],
            test: [],
            answers: [],

            true_answer:0,
        }
    },
    props:[
        'id_course',
        'id_test',
    ],
    methods:{
        async testGet(){
            const res = await axios.post(`/test/${this.id_test}/get`);
            this.test = res.data.test[0];
            console.log(res);
        },

        // ПРoвоеряет какие варианты ответов выбрал ПОЛЬЗОВАТЕЛЬ !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        answerUser(quest_key, quest_id, answer_id, type, status, event){
            if(type == 'radio'){
                this.answers[quest_key] = {quest_id: quest_id, type:type, answers_id:answer_id, status: status};
            }
            
            if(type == 'checkbox'){
                if(!this.answers[quest_key]){
                    this.answers[quest_key] = {quest_id: quest_id, type:type, answers_id:[]};
                }

                const indexValue = this.answers[quest_key].answers_id.indexOf(answer_id);
                if(indexValue === -1) {
                    this.answers[quest_key].answers_id.push(answer_id);
                } else {
                    this.answers[quest_key].answers_id.splice(indexValue, 1);
                }
            }

            if(type == 'input'){
                this.answers[quest_key] = {quest_id: quest_id, type:'input', user_answers: event.target.value, correct_answer:status}
            }

            if(type == 'textarea'){
                this.answers[quest_key] = {quest_id: quest_id, type:'textarea', user_answers: event.target.value}
            }
        },

        // Валидация теста!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        async checkUserTest(){
            console.log(this.answers);
            this.true_answer=0;
            let isValid = true;

            // console.log("length answer user - ", lengthAnswersTrue.length);

            //Сама валидация
            if (this.answers.length === 0) {
                isValid = false;
                console.log("Вы не ответили ни на один вопрос!");
            } else {
                // Сама валидация
                this.test.quest.forEach((quest, index) => {
                    const userAnswer = this.answers[index];

                    // Проверяем, есть ли ответ на вопрос
                    if (!userAnswer || 
                        (userAnswer.type === 'checkbox' && !userAnswer.answers_id.length) || 
                        (userAnswer.type === 'input' && userAnswer.user_answers === '') || 
                        (userAnswer.type === 'textarea' && userAnswer.user_answers === '')) {
                        isValid = false;
                        console.log(index + 1, "Вы ответили не на все вопросы !!!");
                    }
                });
            }

            if(isValid){
                // проверка правильных ответов
                this.answers.forEach((elem, index)=>{
                    if(elem.type == 'radio' && elem.status){
                        this.true_answer++;
                    }

                    if(elem.type == 'checkbox'){
                        this.test.quest.forEach(questElem =>{
                            const correctAnswer = questElem.answer.filter(answer => answer.type == 'checkbox' && answer.status).map(mapElem => mapElem.id).sort();
                            const userAnswer = elem.answers_id.sort()
                            if(JSON.stringify(correctAnswer) == JSON.stringify(userAnswer)){
                                this.true_answer++;
                            }
                        });
                    }

                    if(elem.type == 'input' && (elem.user_answers == elem.correct_answer)){
                        this.true_answer++;
                    }
                });

                // console.log(`Ваш результат ${this.true_answer} или ${(this.true_answer/this.answers.length)*100}%`);
                let score = Math.round((this.true_answer/this.answers.length)*100);

                // const res = await axios.post('/test', {
                //     course_id: this.id_course,
                //     test_id: this.id_test,
                //     answer_user: this.answers,
                //     score: score
                // }).catch((error)=>{
                //     console.log(error);
                // });

                // console.log(res);
            }
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
            <div class=" bg-white py-[30px] px-[76px] rounded-[10px] min-h-[1020px] max-sm:px-5 max-sm:text-xs">
                <h1 class=" text-2xl text-blue-600 font-semibold mb-[30px]">{{test.test_name}}</h1>

                <div v-for="(quest, key) in test.quest" :key="key" class=" flex mb-5">
                    <span class=" font-semibold text-blue-600">{{ key+1 }}.</span>
                    <div class="ml-2">
                        <div class="font-semibold">{{quest.quest_text}}</div>
                        <div v-for="(questAnswer, keyAnswer) in quest.answer" :key="keyAnswer">
                            <div v-if="questAnswer.type == 'radio'">
                                <div class=" flex items-center mb-1"> 
                                    <input @change="answerUser(key, quest.id, questAnswer.id, questAnswer.type, questAnswer.status)" type="radio" :name="'text'+key" :value="questAnswer.status" id="">
                                    <label class=" ml-2" for="">{{ questAnswer.answer_text }}</label>
                                </div>
                            </div>

                            <div v-if="questAnswer.type == 'checkbox'">
                                <div class=" flex items-center mb-1">
                                    <input @change="answerUser(key, quest.id, questAnswer.id, questAnswer.type, questAnswer.status)" type="checkbox" :name="'text'+key" :value="questAnswer.status" id="">
                                    <label class=" ml-2" for="">{{ questAnswer.answer_text }}</label>
                                </div>
                            </div>

                            <div v-if="questAnswer.type == 'input'">
                                <input @input="answerUser(key, quest.id, questAnswer.id, questAnswer.type, questAnswer.answer_text, $event)" type="text" class=" mt-1 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[30px] w-[363px] pl-[10px] max-sm:w-[250px]" :name="'text'+key" id="" placeholder="Введите ответ">
                            </div>

                            <div v-if="questAnswer.type == 'textarea'">
                                <textarea @input="answerUser(key, quest.id, questAnswer.id, questAnswer.type, questAnswer.answer_text, $event)" class=" mt-1 focus:outline-none focus:border-2 focus:border-blue-800 border-2 border-gray-400 rounded placeholder:font-light h-[150px] w-[363px] pl-[10px] resize-none max-sm:w-[250px]" :name="'text'+key" id="" placeholder="Введите ответ"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" flex justify-end">
                    <button class=" py-[6px] px-[20px] rounded hover:bg-green-200 hover:text-green-600 transition ease-in ml-5 text-lg" @click.prevent="checkUserTest">Завершить тест</button>
                </div>
            </div>
        </div>
    </div>
</template>
