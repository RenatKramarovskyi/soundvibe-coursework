import React, {useEffect, useState} from 'react';
import {useParams} from "react-router-dom";
import cls from './PostPage.module.css'
import {useFetching} from "../../hooks/useFetchig.ts";
import axios from "axios";
import {Post, Comment} from "../../types/types.ts";
import {getToken} from "../../utils/getToken.ts";
import CommentBlock from "./post-page-ui/CommentBlock/CommentBlock.tsx";
import CommentList from "./post-page-ui/CommentList/CommentList.tsx";
import {Simulate} from "react-dom/test-utils";
import input = Simulate.input;
import {Send} from "lucide-react";
import { jwtDecode } from "jwt-decode";

const PostPage = () => {

    const { id } = useParams();
    const [post, setPost] = useState<Post>()
    const [comments, setComments] = useState<Comment[]>([])

    const [commentInput, setCommentInput] = useState('')

    const [fetchPost, isPostLoading, fetchPostError] = useFetching( async () => {
            const postResponse = await axios.get<Post>(`/api/post/${id}`, {
                headers: getToken()
            });
            // console.log(postResponse.data)
            setPost(postResponse.data)
        }
    )

    const [fetchComments, isCommentsLoading, fetchCommentsError] = useFetching( async () => {
            const commentResponse = await axios.get<Comment[]>(`/api/comments/${id}`, {
                headers: getToken()
            });
            // console.log(commentResponse.data)
            setComments(commentResponse.data)
        }
    )
    useEffect(() => {
        fetchPost()
        fetchComments()
    }, []);

    const handleSendComment = async () => {
        const userInfo = jwtDecode(localStorage.getItem('token'));

        const sendCommentResponse = await axios.post(`/api/comment`,
            {
                content: commentInput,
                postId: id,
            },
            {
                headers: getToken(),
            }
        );
        fetchComments()
        setCommentInput('')
    }

    return (
        <div className={cls.container}>
            <div className={cls.postContainer}>
                <div className={cls.postArea}>
                    {
                        isPostLoading
                            ?  <div>Loading...</div>
                            : <div>
                                <div className={cls.postPhoto}>
                                    PHOTO
                                </div>
                                <div className={cls.postContent}>
                                    <div className={cls.title}>{post?.title}</div>
                                    <div className={cls.content}>{post?.content}</div>
                                    <div className={cls.category}>{post?.category}</div>
                                </div>

                            </div>
                    }
                </div>
                <div className={cls.commentsListArea}>
                    <CommentList
                        comments={comments}
                        isCommentsLoading={isCommentsLoading}
                    />
                </div>
            </div>
            <div className={cls.inputContainer}>
                <input
                    type="text"
                    value={commentInput}
                    onChange={(e) =>
                        setCommentInput(e.target.value)}
                />
                <button>
                    <Send size={32} onClick={handleSendComment}/>
                </button>
            </div>
        </div>

    );
};

export default PostPage;