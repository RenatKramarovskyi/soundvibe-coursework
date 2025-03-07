import {Link} from "react-router-dom";
import cls from './registrationPage.module.css'
import {useState} from "react";
import DefaultInput, {InputVariant} from "../../components/DefaultInput/DefaultInput.tsx";
import DefaultButton, {DefaultButtonVariant} from "../../components/DefaultButton/DefaultButton.tsx";
import {useAuth} from "../../context/AuthContext.tsx";


interface RegistrationInfoFields {
    username: string;
    email: string;
    password: string;
    repeatPassword: string;
    sex: boolean;
}

const RegistrationPage = () => {

    const [userInfo, setUserInfo] = useState<RegistrationInfoFields>({
        username: "",
        email: "",
        password: "",
        repeatPassword: "",
        sex: false,
    })
    const {register} = useAuth();

    const submitHandler = (event) => {
        event.preventDefault()
        const {repeatPassword, ...registerData} = userInfo;
        register(registerData)
    }

    const changeInputHandler = (event) => {
        console.log('pressed from' + event.target.value)
       setUserInfo({
           ...userInfo,
            [event.target.name]: event.target.value
       })
    }

    return (
        <div className={cls.container}>
            <div>
                <form action="" onSubmit={submitHandler} className={cls.registerForm}>
                    <DefaultInput
                        type={InputVariant.TEXT}
                        name={"username"}
                        value={userInfo?.username}
                        onChange={changeInputHandler}
                        placeholder={"Enter the name"}/>
                    <DefaultInput
                        type={InputVariant.EMAIL}
                        name={"email"}
                        value={userInfo?.email}
                        onChange={changeInputHandler}
                        placeholder={"test-email@gmail.com"}/>
                    <div className={cls.passwordBlock}>
                        <DefaultInput
                            type={InputVariant.PASSWORD}
                            name={"password"}
                            value={userInfo?.password}
                            onChange={changeInputHandler}
                            placeholder={"password..."}
                           />
                        <DefaultInput
                            type={InputVariant.PASSWORD}
                            name={"repeatPassword"}
                            value={userInfo?.repeatPassword}
                            onChange={changeInputHandler}
                            placeholder={"repeat the password..."}
                            />
                    </div>
                    <select name="sex" id="sex" value={userInfo?.sex.toString()} onChange={changeInputHandler}>
                        <option value="true">Male</option>
                        <option value="false">Female</option>
                    </select>

                    <DefaultButton type={DefaultButtonVariant.SUBMIT}>SIGN UP</DefaultButton>
                </form>
                <div className={cls.toLoginPage}>
                    <p>Already have an account?</p>
                    <Link to={'/login'}>LOG IN</Link>
                </div>
            </div>

        </div>
    );
};

export default RegistrationPage;