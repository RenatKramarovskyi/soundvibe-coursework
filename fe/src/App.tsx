import './App.css'
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import HomePage from "./pages/home-page/HomePage.tsx";
import LoginPage from "./pages/login-page/LoginPage.tsx";
import PrivateRoute from "./utils/Router/privateRoute.tsx";
import ProfilePage from "./pages/profile-page/ProfilePage.tsx";
import CategoryPage from "./pages/concrete-category-page/CategoryPage.tsx";
import RegistrationPage from "./pages/registration-page/RegistrationPage.tsx";
import {AuthProvider} from "./context/AuthContext.tsx";
import PostPage from "./pages/concrete-post-page/PostPage.tsx";

function App() {

  return (
   <Router>
       <AuthProvider>
           <Routes>
               <Route path={'/'} element={<PrivateRoute/>}>
                   <Route path={'home'} element={<HomePage/>} />
                   <Route path={'profile'} element={<ProfilePage/>} />
                   <Route path={'category/:category'} element={<CategoryPage/>} />
                   <Route path={'post/:id'} element={<PostPage/>} />
               </Route>
               <Route path={'/login'} element={<LoginPage/>} />
               <Route path={'/register'} element={<RegistrationPage/>} />
           </Routes>
       </AuthProvider>
   </Router>
  )
}

export default App
