import { BrowserRouter, Routes, Route } from 'react-router-dom'
import { useEffect } from 'react'
import { useImmerReducer } from 'use-immer'
import axios from 'axios'

// Context
import StateContext from './StateContext'
import DispatchContext from './DispatchContext'

// Components
import Header from './Components/Header'
import HomeGuest from './Components/HomeGuest'
import Home from './Components/Home'
import CreatePost from './Components/CreatePost'
import Toats from './Components/Toasts'

// Define BaseURL
axios.defaults.baseURL = 'http://blogapil.devel/api/v1/'

function App () {
  const initialState = {
    isLoggedIn: Boolean(localStorage.getItem('token')),
    user: JSON.parse(localStorage.getItem('user')),
    userToken: localStorage.getItem('token'),
    toasts: []
  }

  const reducer = (draft, action) => {
    if (action.type === 'login') {
      draft.isLoggedIn = true
      draft.user = action.data.user
      draft.userToken = action.data.token
      return
    }

    if (action.type === 'logout') {
      draft.isLoggedIn = false
      draft.user = {}
      draft.userToken = ''
      return
    }

    if (action.type === 'addToast') {
      draft.toasts.push(action.data)
    }
  }

  const [state, dispatch] = useImmerReducer(reducer, initialState)

  useEffect(() => {
    if (state.isLoggedIn) {
      const user = JSON.stringify(state.user)
      localStorage.setItem('user', user)
      localStorage.setItem('token', state.userToken)
    } else {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
    }
  }, [state.isLoggedIn])

  return (
    <StateContext.Provider value={state}>
      <DispatchContext.Provider value={dispatch}>
        <BrowserRouter>
          <Header/>
          <Routes>
            <Route path='/' element={state.isLoggedIn ? <Home/> : <HomeGuest/>} />
            <Route path='/create-post' element={<CreatePost />}/>
          </Routes>
          <Toats/>
        </BrowserRouter>
      </DispatchContext.Provider>
    </StateContext.Provider>
  )
}

export default App
