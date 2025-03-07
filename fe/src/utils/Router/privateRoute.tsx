import { Navigate, Outlet } from "react-router-dom";
import Navbar from "../../components/Navbar/Navbar.tsx";
import { useAuth } from "../../context/AuthContext.tsx";
import cls from './outlet.module.css';

const PrivateRoute = () => {
    const { isAuthenticated } = useAuth();

    return (
        isAuthenticated ? (
            <div className={cls.mainContainer}>
                <Navbar />
                <div className={cls.outletContainer}>
                    <Outlet />
                </div>
            </div>
        ) : (
            <Navigate to="login" />
        )
    );
};

export default PrivateRoute;
