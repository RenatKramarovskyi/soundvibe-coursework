import React, {useEffect, useRef, useState} from 'react';
import cls from './ProfilePage.module.css'
import {useFetching} from "../../hooks/useFetchig.ts";
import axios from "axios";
import {getToken} from "../../utils/getToken.ts";
import CommentList from "./profile-page-ui/CommentList/CommentList.tsx";
import {jwtDecode} from "jwt-decode";
import useHover from "../../hooks/useHover.ts";
import {User} from "../../types/types.ts";
const ProfilePage = () => {

    const [userComments, setUserComments] = useState<Comment[]>([])
    const [user, setUser] = useState<User>()
    const passwordRef = useRef(null);


    const isHovered = useHover(passwordRef);


    const [fetchComments, isLoading, error] = useFetching(async () => {
        const commentResponse = await axios.get<Comment[]>(`/api/comment/by-user-id/${jwtDecode(localStorage.getItem('token')).id}`, {
            headers: getToken()
        });
        setUserComments(commentResponse.data)
    })

    const [fetchUser, isUserLoading, userError] = useFetching(async () => {
        const userResponse = await axios.get<User>(`/api/user/${jwtDecode(localStorage.getItem('token')).id}`, {
            headers: getToken()
        });
        setUser(userResponse.data)
    })

    useEffect(() => {
        fetchComments()
        fetchUser()
    }, []);

    return (
        <div className={cls.container}>
            <div className={cls.profileBlockArea}>
                <div className={cls.userInfoArea}>
                    {
                        isUserLoading
                            ? <div>Loading...</div>
                            :  <div>
                                    <div className={cls.userInfoField}>Unique identifier - {user?.id}</div>
                                    <div className={cls.userInfoField}>Username - {user?.username}</div>
                                    <div className={cls.userInfoField}>Email - {user?.email}</div>
                                    <div
                                        className={cls.userInfoField}
                                        ref={passwordRef}
                                    >
                                        {isHovered ? user?.password : "*********"}
                                    </div>
                                    <div className={cls.userInfoField}>Sex : {user?.sex ? "Male" : "Female"}</div>
                            </div>
                    }

                </div>
                <div className={cls.commentsListArea}>
                    <CommentList comments={userComments} isCommentsLoading={isLoading}/>
                </div>
            </div>
        </div>
    );
};

export default ProfilePage;