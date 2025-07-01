<script>
import ComboComponent from "@/Layout/Combo.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
export default{
    data(){
        return{
            modalFlag: false,
            flagLoadCourse: false,
            flagLoadStudent: false,
            flagLoadStudentCourse: false,
            // test_course: this.tests,

            name: null,
            id: 0,
            page: 1,
            pageStudents: 1,
            pageStudentsCourse: 1,
            tests: [],
            students:[],
            student_course:[],
        }
    },
    methods:{
        async deleteTest(index, id){
            const deleteTestAgree = confirm ("Вы уверены что хоитие удалить тест");
            if(deleteTestAgree){
                this.tests.splice(index,1);
                const res = await axios.post(route('admin.delete.test', id));
            }
        },
        async addStudent(idStudent, index){
            this.student_course.push(this.students[index]);
            this.students.splice(index, 1);
            const res = await axios.post(`/admin/add_student/${idStudent}/course/${this.id}`);
        },
        async deleteStudent(idStudent,index){
            this.students.push(this.student_course[index]);
            this.student_course.splice(index, 1);
            const res = await axios.post(`/admin/delete_student/${idStudent}/course/${this.id}`);
        },
        async dataCourse(){
            const res = await axios.post(`/admin/course/${this.course_id}/data?page=${this.page}`);
            // this.result = res;
            this.name = res.data.course_name;
            this.id = res.data.course_id;
            this.tests.push(...res.data.tests.data);

            // this.students = res.data.users_for_add;
            // this.student_course = res.data.users_in_course;

            this.students = this.students.filter((item) => !this.student_course.some(elem=> elem.id === item.id));
            
            ++this.page;
            if(res.data.tests.next_page_url == null){
                return this.flagLoadCourse = true;
            }
        },
        async dataStudents(){
            const res = await axios.post(`/admin/course/${this.course_id}/dataStudents?page=${this.pageStudents}`);
            this.students.push(...res.data.users_for_add.data);

            ++this.pageStudents;
            if(res.data.users_for_add.next_page_url == null){
                return this.flagLoadStudent = true;
            }
        },
        async dataStudentsCourse(){
            const res = await axios.post(`/admin/course/${this.course_id}/dataStudentsCourse?page=${this.pageStudentsCourse}`);
            this.student_course.push(...res.data.users_in_course);
            console.log(res);

            ++this.pageStudentsCourse;
            if(res.data.users_in_course.length == 0){
                return this.flagLoadStudentCourse = true;
            }
        },
        scrollStudent(event){
            const el = event.target;
            if (el.scrollTop + el.clientHeight >= el.scrollHeight - 1) {
                if(!this.flagLoadStudent){
                    this.dataStudents();
                }
            }
        },
        scrollStudentCourse(event){
            const el = event.target;
            if (el.scrollTop + el.clientHeight >= el.scrollHeight - 1) {
                if(!this.flagLoadStudentCourse){
                    this.dataStudentsCourse();
                }
                // this.dataStudentsCourse();
            }
        },
        modalFun(){
            this.modalFlag = !this.modalFlag;
            this.dataStudents();
            this.dataStudentsCourse();
        },
    },
    props:[
        'course_id'
    ],
    components:{
        Link,
        ComboComponent,
    },
    mounted(){
        this.dataCourse();
    }
}
</script>

<template>
    <ComboComponent>
        <!-- <div class=" py-[30px] px-[77px] bg-white rounded-xl min-h-[780px]"> -->
        <div class=" py-[30px] px-4 min-[490px]:px-[77px] bg-white rounded-xl min-h-[780px]">
            <div class="flex items-center justify-center max-[525px]:flex-col">
                <h1 class=" font-semibold text-2xl text-center">{{ name }}</h1>
                <button @click.prevent="modalFun" class="px-[30px] py-[10px] hover:bg-blue-200 
                hover:text-blue-600 rounded-md my-[10px] text-lg transition ease-in bg-gray-100 ml-5 max-[525px]:ml-0">Настроить студентов</button>
            </div>

            <div class="min-h-[678px] mt-5">
                <div class="mt-3 flex flex-col items-center md:items-start min-[450px]:items-normal mx-5">
                    <Link :href="route('admin.create.test', id)" class="truncate group w-[186px] xl:w-[208px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer mr-1 xl:mr-2 mb-[10px] hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                        <div class="font-semibold text-lg truncate text-wrap">Создать новый тест</div>
                    </Link>
                </div>

                <div class=" mt-3 flex flex-col items-center md:items-start min-[450px]:items-normal">
                    <p class=" mb-2 mx-auto md:mx-0 text-gray-500">Ваши тесты</p>
                    <div class="flex flex-wrap w-[186px] min-[450px]:w-[380px] mx-5 md:w-full">
                        <div v-for="(elem, index) in this.tests" :key="elem">
                            <div class="truncate group w-[186px] xl:w-[208px] min-h-16 border-2 border-gray-400 rounded-md p-3 cursor-pointer mr-1 xl:mr-2 mb-[10px] hover:border-blue-600 hover:bg-blue-300 hover:text-blue-600 transition ease-in">
                                <h1 class="font-semibold text-lg truncate">{{elem.test_name}}</h1>
                                <p @click.prevent="deleteTest(index, elem.id)" class=" text-red-600 group-hover:text-blue-600">Удалить тест</p>
                            </div>
                        </div>
                    </div>
                    <div class=" flex justify-end w-full">
                        <button v-if="!flagLoadCourse" @click.prevent="dataCourse()" class="px-[30px] py-[10px] hover:bg-blue-200 
                    hover:text-blue-600 rounded-md my-[10px] text-lg transition ease-in bg-gray-100">Загрузить еще...</button>
                    </div>
                </div>
            </div>
        </div>
    </ComboComponent>

    <div v-if="modalFlag">
        <div class=" bg-black bg-opacity-50 h-full w-full flex items-center justify-center fixed top-0 left-0 text-lg">
            <div class=" min-w-[400px] max-[490px]:min-w-[320px] max-[490px]:px-4 py-8 px-[77px] bg-white rounded-md">
                <h1 class=" font-bold text-center mb-5">Студенты курса</h1>
                <div v-if="students.length != 0" class=" overflow-auto  h-[150px]" @scroll="scrollStudent">
                    <div v-for="(elem, index) in students" :key="index" class=" flex items-center justify-between mt-2">
                        <div class=" flex items-center w-80">
                            <div class=" w-8">
                                <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.7275 1.5C18.9866 1.5002 22.4021 4.89789 22.4023 9.04395C22.4023 13.1902 18.9868 16.5887 14.7275 16.5889C10.4681 16.5889 7.05176 13.1903 7.05176 9.04395C7.05199 4.89777 10.4683 1.5 14.7275 1.5Z" stroke="black" stroke-width="3"/>
                                    <path d="M3 22.0713H27C27.8284 22.0713 28.5 22.7429 28.5 23.5713V34.5H1.5V23.5713C1.5 22.7946 2.09028 22.1559 2.84668 22.0791L3 22.0713Z" stroke="black" stroke-width="3"/>
                                    </svg>
                            </div>
                            <div class=" ml-10">
                                <h1 class=" font-semibold text-lg">{{ elem.name }}</h1>
                                <p class=" text-sm text-gray-600">{{elem.email}}</p>
                            </div>
                        </div>
                        <div>
                            <button class=" text-4xl px-[5px] py-[1px] hover:bg-blue-200 hover:text-blue-600 rounded-md flex items-center justify-center" @click.prevent="addStudent(elem.id, index)">+</button>
                        </div>
                    </div>
                </div>
                
                

                <div class="mt-5">
                    <div v-if="student_course.length">  
                        <p class=" text-gray-500 font-semibold text-base">Студенты</p>
                        <div class=" overflow-auto  h-[150px]" @scroll="scrollStudentCourse">
                            <div v-for="(elem, index) in student_course" :key="index">
                                <div class=" flex items-center justify-between mt-2">
                                    <div class=" flex items-center w-80">
                                        <div class=" w-8">
                                            <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.7275 1.5C18.9866 1.5002 22.4021 4.89789 22.4023 9.04395C22.4023 13.1902 18.9868 16.5887 14.7275 16.5889C10.4681 16.5889 7.05176 13.1903 7.05176 9.04395C7.05199 4.89777 10.4683 1.5 14.7275 1.5Z" stroke="black" stroke-width="3"/>
                                                <path d="M3 22.0713H27C27.8284 22.0713 28.5 22.7429 28.5 23.5713V34.5H1.5V23.5713C1.5 22.7946 2.09028 22.1559 2.84668 22.0791L3 22.0713Z" stroke="black" stroke-width="3"/>
                                                </svg>
                                        </div>
                                        <div class=" ml-10">
                                            <h1 class=" font-semibold text-lg">{{ elem.name }}</h1>
                                            <p class=" text-sm text-gray-600">{{elem.email}}</p>
                                        </div>
                                    </div>
                                    <div @click.prevent="deleteStudent(elem.id, index)" class=" group">
                                        <div class="group-hover:hidden p-2 rounded-md">
                                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.48837 1.06383C6.48837 0.476293 6.95691 0 7.53488 0H10.4651C11.0431 0 11.5116 0.476293 11.5116 1.06383V2.34043H16.9535C17.5315 2.34043 18 2.81672 18 3.40426C18 3.99179 17.5315 4.46809 16.9535 4.46809H1.04651C0.46854 4.46809 0 3.99179 0 3.40426C0 2.81672 0.468539 2.34043 1.04651 2.34043H6.48837V1.06383ZM7.32558 2.34043H10.6744V1.91489C10.6744 1.32736 10.2059 0.851064 9.62791 0.851064H8.37209C7.79412 0.851064 7.32558 1.32736 7.32558 1.91489V2.34043Z" fill="black"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.3023 2.55319V1.06383C11.3023 0.5938 10.9275 0.212766 10.4651 0.212766H7.53488C7.07251 0.212766 6.69767 0.5938 6.69767 1.06383V2.55319H1.04651C0.584134 2.55319 0.209302 2.93423 0.209302 3.40426C0.209302 3.87428 0.584134 4.25532 1.04651 4.25532H16.9535C17.4159 4.25532 17.7907 3.87428 17.7907 3.40426C17.7907 2.93423 17.4159 2.55319 16.9535 2.55319H11.3023ZM7.11628 2.55319V1.91489C7.11628 1.20985 7.67853 0.638298 8.37209 0.638298H9.62791C10.3215 0.638298 10.8837 1.20985 10.8837 1.91489V2.55319H7.11628ZM6.48837 2.34043H1.04651C0.468539 2.34043 0 2.81672 0 3.40426C0 3.99179 0.46854 4.46809 1.04651 4.46809H16.9535C17.5315 4.46809 18 3.99179 18 3.40426C18 2.81672 17.5315 2.34043 16.9535 2.34043H11.5116V1.06383C11.5116 0.476293 11.0431 0 10.4651 0H7.53488C6.95691 0 6.48837 0.476293 6.48837 1.06383V2.34043ZM10.6744 1.91489C10.6744 1.32736 10.2059 0.851064 9.62791 0.851064H8.37209C7.79412 0.851064 7.32558 1.32736 7.32558 1.91489V2.34043H10.6744V1.91489Z" fill="black"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.326 6.17028C16.326 5.58274 15.8574 5.10645 15.2795 5.10645H2.72132C2.14334 5.10645 1.6748 5.58274 1.6748 6.17027V18.9362C1.6748 19.5238 2.14334 20.0001 2.72132 20.0001H15.2795C15.8574 20.0001 16.326 19.5238 16.326 18.9362V6.17028ZM5.44225 6.91496C5.44225 6.50368 5.11427 6.17028 4.70969 6.17028V6.17028C4.30511 6.17028 3.97713 6.50368 3.97713 6.91496V18.1916C3.97713 18.6028 4.30511 18.9362 4.70969 18.9362C5.11427 18.9362 5.44225 18.6028 5.44225 18.1916V6.91496ZM8.16318 6.91496C8.16318 6.50368 8.49115 6.17028 8.89573 6.17028V6.17028C9.30032 6.17028 9.62829 6.50368 9.62829 6.91496V18.1916C9.62829 18.6028 9.30032 18.9362 8.89573 18.9362C8.49115 18.9362 8.16318 18.6028 8.16318 18.1916V6.91496ZM13.8143 6.91496C13.8143 6.50368 13.4864 6.17028 13.0818 6.17028V6.17028C12.6772 6.17028 12.3492 6.50368 12.3492 6.91496V18.1916C12.3492 18.6028 12.6772 18.9362 13.0818 18.9362C13.4864 18.9362 13.8143 18.6028 13.8143 18.1916V6.91496Z" fill="black"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.2795 5.31921H2.72132C2.25894 5.31921 1.88411 5.70025 1.88411 6.17027V18.9362C1.88411 19.4063 2.25894 19.7873 2.72132 19.7873H15.2795C15.7418 19.7873 16.1167 19.4063 16.1167 18.9362V6.17028C16.1167 5.70025 15.7418 5.31921 15.2795 5.31921ZM3.76783 18.1916V6.91496C3.76783 6.38617 4.18951 5.95751 4.70969 5.95751C5.22986 5.95751 5.65155 6.38617 5.65155 6.91496V18.1916C5.65155 18.7203 5.22986 19.149 4.70969 19.149C4.18951 19.149 3.76783 18.7203 3.76783 18.1916ZM9.8376 6.91496V18.1916C9.8376 18.7203 9.41591 19.149 8.89573 19.149C8.37556 19.149 7.95387 18.7203 7.95387 18.1916V6.91496C7.95387 6.38617 8.37556 5.95751 8.89573 5.95751C9.41591 5.95751 9.8376 6.38617 9.8376 6.91496ZM12.1399 18.1916V6.91496C12.1399 6.38617 12.5616 5.95751 13.0818 5.95751C13.602 5.95751 14.0236 6.38617 14.0236 6.91496V18.1916C14.0236 18.7203 13.602 19.149 13.0818 19.149C12.5616 19.149 12.1399 18.7203 12.1399 18.1916ZM15.2795 5.10645C15.8574 5.10645 16.326 5.58274 16.326 6.17028V18.9362C16.326 19.5238 15.8574 20.0001 15.2795 20.0001H2.72132C2.14334 20.0001 1.6748 19.5238 1.6748 18.9362V6.17027C1.6748 5.58274 2.14334 5.10645 2.72132 5.10645H15.2795ZM4.70969 6.17028C5.11427 6.17028 5.44225 6.50368 5.44225 6.91496V18.1916C5.44225 18.6028 5.11427 18.9362 4.70969 18.9362C4.30511 18.9362 3.97713 18.6028 3.97713 18.1916V6.91496C3.97713 6.50368 4.30511 6.17028 4.70969 6.17028ZM8.89573 6.17028C8.49115 6.17028 8.16318 6.50368 8.16318 6.91496V18.1916C8.16318 18.6028 8.49115 18.9362 8.89573 18.9362C9.30032 18.9362 9.62829 18.6028 9.62829 18.1916V6.91496C9.62829 6.50368 9.30032 6.17028 8.89573 6.17028ZM13.0818 6.17028C13.4864 6.17028 13.8143 6.50368 13.8143 6.91496V18.1916C13.8143 18.6028 13.4864 18.9362 13.0818 18.9362C12.6772 18.9362 12.3492 18.6028 12.3492 18.1916V6.91496C12.3492 6.50368 12.6772 6.17028 13.0818 6.17028Z" fill="black"/>
                                            </svg>
                                        </div>
                                        <div class=" group-hover:block hidden group-hover:bg-red-200 p-2 rounded-md">
                                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.48837 1.06383C6.48837 0.476293 6.95691 0 7.53488 0H10.4651C11.0431 0 11.5116 0.476293 11.5116 1.06383V2.34043H16.9535C17.5315 2.34043 18 2.81672 18 3.40426C18 3.99179 17.5315 4.46809 16.9535 4.46809H1.04651C0.46854 4.46809 0 3.99179 0 3.40426C0 2.81672 0.468539 2.34043 1.04651 2.34043H6.48837V1.06383ZM7.32558 2.34043H10.6744V1.91489C10.6744 1.32736 10.2059 0.851064 9.62791 0.851064H8.37209C7.79412 0.851064 7.32558 1.32736 7.32558 1.91489V2.34043Z" fill="#b91c1c"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.3023 2.55319V1.06383C11.3023 0.5938 10.9275 0.212766 10.4651 0.212766H7.53488C7.07251 0.212766 6.69767 0.5938 6.69767 1.06383V2.55319H1.04651C0.584134 2.55319 0.209302 2.93423 0.209302 3.40426C0.209302 3.87428 0.584134 4.25532 1.04651 4.25532H16.9535C17.4159 4.25532 17.7907 3.87428 17.7907 3.40426C17.7907 2.93423 17.4159 2.55319 16.9535 2.55319H11.3023ZM7.11628 2.55319V1.91489C7.11628 1.20985 7.67853 0.638298 8.37209 0.638298H9.62791C10.3215 0.638298 10.8837 1.20985 10.8837 1.91489V2.55319H7.11628ZM6.48837 2.34043H1.04651C0.468539 2.34043 0 2.81672 0 3.40426C0 3.99179 0.46854 4.46809 1.04651 4.46809H16.9535C17.5315 4.46809 18 3.99179 18 3.40426C18 2.81672 17.5315 2.34043 16.9535 2.34043H11.5116V1.06383C11.5116 0.476293 11.0431 0 10.4651 0H7.53488C6.95691 0 6.48837 0.476293 6.48837 1.06383V2.34043ZM10.6744 1.91489C10.6744 1.32736 10.2059 0.851064 9.62791 0.851064H8.37209C7.79412 0.851064 7.32558 1.32736 7.32558 1.91489V2.34043H10.6744V1.91489Z" fill="#b91c1c"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.326 6.17028C16.326 5.58274 15.8574 5.10645 15.2795 5.10645H2.72132C2.14334 5.10645 1.6748 5.58274 1.6748 6.17027V18.9362C1.6748 19.5238 2.14334 20.0001 2.72132 20.0001H15.2795C15.8574 20.0001 16.326 19.5238 16.326 18.9362V6.17028ZM5.44225 6.91496C5.44225 6.50368 5.11427 6.17028 4.70969 6.17028V6.17028C4.30511 6.17028 3.97713 6.50368 3.97713 6.91496V18.1916C3.97713 18.6028 4.30511 18.9362 4.70969 18.9362C5.11427 18.9362 5.44225 18.6028 5.44225 18.1916V6.91496ZM8.16318 6.91496C8.16318 6.50368 8.49115 6.17028 8.89573 6.17028V6.17028C9.30032 6.17028 9.62829 6.50368 9.62829 6.91496V18.1916C9.62829 18.6028 9.30032 18.9362 8.89573 18.9362C8.49115 18.9362 8.16318 18.6028 8.16318 18.1916V6.91496ZM13.8143 6.91496C13.8143 6.50368 13.4864 6.17028 13.0818 6.17028V6.17028C12.6772 6.17028 12.3492 6.50368 12.3492 6.91496V18.1916C12.3492 18.6028 12.6772 18.9362 13.0818 18.9362C13.4864 18.9362 13.8143 18.6028 13.8143 18.1916V6.91496Z" fill="#b91c1c"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.2795 5.31921H2.72132C2.25894 5.31921 1.88411 5.70025 1.88411 6.17027V18.9362C1.88411 19.4063 2.25894 19.7873 2.72132 19.7873H15.2795C15.7418 19.7873 16.1167 19.4063 16.1167 18.9362V6.17028C16.1167 5.70025 15.7418 5.31921 15.2795 5.31921ZM3.76783 18.1916V6.91496C3.76783 6.38617 4.18951 5.95751 4.70969 5.95751C5.22986 5.95751 5.65155 6.38617 5.65155 6.91496V18.1916C5.65155 18.7203 5.22986 19.149 4.70969 19.149C4.18951 19.149 3.76783 18.7203 3.76783 18.1916ZM9.8376 6.91496V18.1916C9.8376 18.7203 9.41591 19.149 8.89573 19.149C8.37556 19.149 7.95387 18.7203 7.95387 18.1916V6.91496C7.95387 6.38617 8.37556 5.95751 8.89573 5.95751C9.41591 5.95751 9.8376 6.38617 9.8376 6.91496ZM12.1399 18.1916V6.91496C12.1399 6.38617 12.5616 5.95751 13.0818 5.95751C13.602 5.95751 14.0236 6.38617 14.0236 6.91496V18.1916C14.0236 18.7203 13.602 19.149 13.0818 19.149C12.5616 19.149 12.1399 18.7203 12.1399 18.1916ZM15.2795 5.10645C15.8574 5.10645 16.326 5.58274 16.326 6.17028V18.9362C16.326 19.5238 15.8574 20.0001 15.2795 20.0001H2.72132C2.14334 20.0001 1.6748 19.5238 1.6748 18.9362V6.17027C1.6748 5.58274 2.14334 5.10645 2.72132 5.10645H15.2795ZM4.70969 6.17028C5.11427 6.17028 5.44225 6.50368 5.44225 6.91496V18.1916C5.44225 18.6028 5.11427 18.9362 4.70969 18.9362C4.30511 18.9362 3.97713 18.6028 3.97713 18.1916V6.91496C3.97713 6.50368 4.30511 6.17028 4.70969 6.17028ZM8.89573 6.17028C8.49115 6.17028 8.16318 6.50368 8.16318 6.91496V18.1916C8.16318 18.6028 8.49115 18.9362 8.89573 18.9362C9.30032 18.9362 9.62829 18.6028 9.62829 18.1916V6.91496C9.62829 6.50368 9.30032 6.17028 8.89573 6.17028ZM13.0818 6.17028C13.4864 6.17028 13.8143 6.50368 13.8143 6.91496V18.1916C13.8143 18.6028 13.4864 18.9362 13.0818 18.9362C12.6772 18.9362 12.3492 18.6028 12.3492 18.1916V6.91496C12.3492 6.50368 12.6772 6.17028 13.0818 6.17028Z" fill="#b91c1c"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" flex justify-end">
                    <button class=" px-[30px] py-[10px] hover:bg-blue-200 hover:text-blue-600 rounded-md mt-5 bg-gray-100" @click.prevent="modalFlag=!modalFlag">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>