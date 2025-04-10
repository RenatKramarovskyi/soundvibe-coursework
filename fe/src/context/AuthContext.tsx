import React, {createContext, useContext, useEffect, useState} from 'react';
import {useNavigate} from "react-router-dom";
import axios from "axios";
import {getToken} from "../utils/getToken.ts";
import loginPage from "../pages/login-page/LoginPage.tsx";

interface LoginProps {
    email: string
    password: string
}

interface RegistrationProps {
    username: string,
    email: string,
    password: string,
    sex: boolean
}

const AuthContext = createContext({});
export const AuthProvider = ({children}) => {

    const [isAuthenticated, setIsAuthenticated] = useState<boolean>(
        () => sessionStorage.getItem('isAuthenticated') === 'true'
    )

    const navigate = useNavigate();

    useEffect(() => {
        sessionStorage.setItem('isAuthenticated', isAuthenticated.toString());
    }, [isAuthenticated]);

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            axios.get('/api/check', { headers: getToken() })
                .then(() => {
                    setIsAuthenticated(true)
                    navigate('home')
                })
                .catch(() => {
                    localStorage.removeItem('token');
                    console.log('Token expired, please log in again');
                });

        }
    }, []);


     const login = async (credentials : LoginProps) => {
        try{
            const response = await axios.post('api/login', credentials);
            localStorage.setItem('token', response.data.token)
            setIsAuthenticated(true)
            navigate('home')
        }catch (e){
            console.log('login failed ' + e.message)
        }

    }
    const register = async (credentials : RegistrationProps) => {
        try{
            const createUserResponse = await axios.post('api/user', credentials);
            const loginResponse = await axios.post('api/login', {email: credentials.email, password: credentials.password});
            console.log(loginResponse)
            localStorage.setItem('token', loginResponse.data.token)
            setIsAuthenticated(true)
            navigate('home')
        }catch (e){
            console.log('login failed ' + e.message)
        }
    }

    const logout = () => {
        setIsAuthenticated(false)
        navigate('login')
        localStorage.removeItem('token')
    }


    return (
        <AuthContext.Provider value={{isAuthenticated, login, logout, register}}>
            {children}
        </AuthContext.Provider>
    );
};


export const useAuth = () => {
    const context = useContext(AuthContext);

    if(!context) {
        throw new Error("Error was occurred while authentication")
    }

    return context
}