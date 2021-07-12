<template>
    <breeze-validation-errors class="mb-4" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>
    <div v-if="error">
        {{ error }}
    </div>

    <form v-if="regSteps.getPhone" @submit.prevent="submit">
        <div>
            <breeze-label for="phone" value="Phone" />
            <breeze-input id="phone" type="phone" :disabled="code" class="mt-1 block w-full" v-model="form.phone" required autofocus autocomplete="phone" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Reset Password
            </breeze-button>
        </div>
    </form>
    <form v-if="regSteps.getCode" @submit.prevent="submitCode">
        <div>
            the code sent to: {{ form.phone }}
        </div>
        <div>
            <breeze-label for="code" value="Enter Code" />
            <breeze-input id="code" type="text" class="mt-1 block w-full" v-model="form.code" required autofocus />
        </div>

        <div class="flex items-center justify-end mt-4">
            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Register
            </breeze-button>
        </div>
    </form>
    <form v-if="regSteps.getPassword" @submit.prevent="submitName">

        <div>
            <breeze-label for="password" value="Enter password" />
            <breeze-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required  />
        </div>

        <div>
            <breeze-label for="confirmPassword" value="Confirm password" />
            <breeze-input id="confirmPassword" type="password" class="mt-1 block w-full" v-model="form.confirmPassword" required  />
        </div>

        <div class="flex items-center justify-end mt-4">
            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Enter
            </breeze-button>
        </div>
    </form>
</template>

<script>
import BreezeButton from '@/Components/Button'
import BreezeGuestLayout from "@/Layouts/Guest"
import BreezeInput from '@/Components/Input'
import BreezeCheckbox from '@/Components/Checkbox'
import BreezeLabel from '@/Components/Label'
import BreezeValidationErrors from '@/Components/ValidationErrors'

export default {
    layout: BreezeGuestLayout,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeCheckbox,
        BreezeLabel,
        BreezeValidationErrors
    },

    props: {
        auth: Object,
        errors: Object,
        status: String,
        code: Number,
        smsSent:Boolean,
    },

    data() {
        return {
            error:'',
            form: this.$inertia.form({
                phone: '',
                code:'',
                confirmPassword:'',
                password:'',
            }),
            errorList:{
              err01:'wrong mobile no',
              err02:'wrong code entered',
                err03: 'Failed to send SMS',
            },
            regSteps:{
                getPhone:true,
                getCode:false,
                getPassword:false,
            }
        }
    },

    methods: {
        submitName(){
            this.form.post(this.route('setPassword'), {
                onSuccess: () => {

                }
            });
        },
        submit() {
                this.form.phone=this.toPhoneNumber(this.form.phone);
                if(this.form.phone.match(/^09\d{9}$/g)){
                    this.form.post(this.route('resetPassword'), {
                        onSuccess: () => {
                            return Promise.all([
                                //console.log(this.smsSent),
                                //console.log(this.code)
                            ])
                        },
                        onFinish: () => {
                            if (this.smsSent){
                                this.regSteps.getPhone=false;
                                this.regSteps.getCode=true;
                            }else {
                                this.error=this.errorList.err03;
                            }
                        },
                    });
                }else {
                    this.error=this.errorList.err01;
                }
        },

        submitCode(){
            if (this.form.code == this.code){
                this.error='';
                //alert('right');
                this.regSteps.getCode=false;
                this.regSteps.getPassword=true;
            }else {
                this.error='Wrong Code';
            }
        },
        toPhoneNumber(old){
            old = old.replace(/\+98/g, '0').replace(/\+۹۸/g, '۰').replace(/\+٩٨/g, '٠').replace(/ /g, '');
            let chars = {
                '۰':'0', '۱':'1', '۲':'2', '۳':'3', '۴':'4', '۵':'5', '۶':'6', '۷':'7', '۸':'8', '۹':'9',
                '٠':'0', '١':'1', '٢':'2', '٣':'3', '٤':'4', '٥':'5', '٦':'6', '٧':'7', '٨':'8', '٩':'9',
            };
            old = old.replace(/[۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩]/g, m => chars[m]);
            return old;
        }
    }
}
</script>
