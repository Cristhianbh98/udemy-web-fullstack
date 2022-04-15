// Context
import { useContext } from 'react'
import StateContext from '../StateContext'

// Components
import Toast from './Toast'

const Toats = () => {
  const appState = useContext(StateContext)

  return (
    <div className="fixed-bottom p-2">
      <div className="toats float-end">
        {appState.toasts.map((toast, index) => <Toast key={index} {...toast}/>)}
      </div>
    </div>
  )
}

export default Toats
