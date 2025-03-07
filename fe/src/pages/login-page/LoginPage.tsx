import {useState} from 'react';
import {Link} from "react-router-dom";
import {useAuth} from "../../context/AuthContext.tsx";
import cls from "../registration-page/registrationPage.module.css";
import DefaultInput, {InputVariant} from "../../components/DefaultInput/DefaultInput.tsx";
import DefaultButton, {DefaultButtonVariant} from "../../components/DefaultButton/DefaultButton.tsx";

interface LoginInfoFields {
    email: string;
    password: string;
}

const LoginPage = () => {

    const [userInfo, setUserInfo] = useState<LoginInfoFields>({
        email: "",
        password: "",
    })
    const {login} = useAuth();


    const submitHandler = (event) => {
        event.preventDefault()
        login(userInfo)

    }

    const changeInputHandler = (event) => {
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
                    </div>

                    <DefaultButton
                        type={DefaultButtonVariant.SUBMIT}
                    >LOG IN</DefaultButton>
                </form>
                <div className={cls.toLoginPage}>
                    <p>Don't have an account?</p>
                    <Link to={'/register'}>SIGN UP</Link>
                </div>
            </div>

        </div>
    );
};

export default LoginPage;