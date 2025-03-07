import React, {useEffect, useState} from 'react';
import cls from './HomePage.module.css'
import {Post} from "../../types/types.ts";
import {useFetching} from "../../hooks/useFetchig.ts";
import axios from "axios";
import {getToken} from "../../utils/getToken.ts";
import PostList from "../../components/PostsList/PostList.tsx";
import {useNavigate} from "react-router-dom";
const HomePage = () => {

    const categories = ['general', 'celebrities', 'musical-discoveries', 'musical-novelties'];
    const navigate = useNavigate();

    const [posts, setPosts] = useState<Post[]>([])

    const [fetchPost, isLoading, error] = useFetching( async () => {
            const postResponse = await axios.get<Post[]>(`/api/post`, {
                headers: getToken()
            });
            setPosts(postResponse.data)
        }
    )

    useEffect(() => {
        fetchPost()
    }, []);

    return (
        <div className={cls.container}>

            <div className={cls.contentArea}>
               <div className={cls.categories}>
                   {categories.map(category =>
                       <div
                           key={category}
                           className={cls.categoryItem}
                           onClick={
                           () => navigate(`/category/${category}`, {replace: true})
                       }
                       >{category}</div>
                   )}
               </div>
                <div className={cls.hottestPosts}>
                    <PostList posts={posts}/>
                </div>

            </div>
        </div>
    );
};

export default HomePage;