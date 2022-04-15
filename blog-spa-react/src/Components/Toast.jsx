import { useEffect, useState } from 'react'

const Toast = ({ type = 'primary', title, message }) => {
  const [isActive, setIsActive] = useState(true)

  useEffect(() => {
    const timeOut = setTimeout(() => setIsActive(false), 2500)
    return () => clearTimeout(timeOut)
  }, [])

  return (
    <div className={'toast mb-2 ' + (isActive ? 'show' : 'hide')} role="alert" aria-live="assertive" aria-atomic="true">
      <div className={'toast-header text-white ' + `bg-${type}`}>
        <strong className="me-auto">{title}</strong>
        <button onClick={() => setIsActive(false)} type="button" className="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div className="toast-body">{message}</div>
    </div>
  )
}

export default Toast
