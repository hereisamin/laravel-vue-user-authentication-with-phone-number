<template>
    <breeze-validation-errors class="mb-4" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            {{ error }}
        </div>
        <div v-if="msg">{{ msg }}</div>
        <div>
            <breeze-label for="phone" value="Phone" />
            <breeze-input id="phone" type="tel" class="mt-1 block w-full" v-model="form.phone" required autofocus autocomplete="tel" />
        </div>

        <div class="mt-4">
            <breeze-label for="password" value="Password" />
            <breeze-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
        </div>

        <div class="block mt-4">
            <label class="flex items-center">
                <breeze-checkbox name="remember" v-model:checked="form.remember" />
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <inertia-link :href="route('resetPassword')" class="underline text-sm text-gray-600 hover:text-gray-900">
                Forgot your password?
            </inertia-link>

            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Log in
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
            canResetPassword: Boolean,
            errors: Object,
            status: String,
            msg:String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    phone: '',
                    password: '',
                    remember: false
                }),
                error:'',
                errorList:{
                    err01:'wrong mobile no',
                },
            }
        },

        methods: {
            submit() {
                this.form.phone=this.toPhoneNumber(this.form.phone);
                if(this.form.phone.match(/^09\d{9}$/g)){
                    this.form.post(this.route('phoneLogin'), {
                        onFinish: () => this.form.reset('password'),
                    })
                }else {
                    this.error=this.errorList.err01;
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
