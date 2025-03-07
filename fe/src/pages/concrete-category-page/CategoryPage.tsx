import React, {useEffect, useState} from 'react';
import {useParams} from "react-router-dom";
import {useFetching} from "../../hooks/useFetchig.ts";
import axios from "axios";
import {getToken} from "../../utils/getToken.ts";
import {Post} from "../../types/types.ts";
import PostList from "../../components/PostsList/PostList.tsx";
import cls from './CategoryPage.module.css'

const CategoryPage = () => {

    const { category } = useParams();
    const [posts, setPosts] = useState<Post[]>([])

    const [fetchPost, isLoading, error] = useFetching( async () => {
            const postResponse = await axios.get<Post[]>(`/api/posts-by-category/${category}`, {
                headers: getToken()
            });
            setPosts(postResponse.data)
        }
    )

    useEffect(() => {
        fetchPost()
    }, [category]);

    return (
        <div className={cls.container}>
            {error && <div>{error}</div>}
            <div className={cls.postArea}>
                {
                    isLoading
                        ?  <div>Loading...</div>
                        : <PostList posts={posts}/>
                }
            </div>
        </div>
    );
};

export default CategoryPage;