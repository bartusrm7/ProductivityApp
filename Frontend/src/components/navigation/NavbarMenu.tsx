import { FaRegUserCircle } from "react-icons/fa";

export default function NavbarMenu({ pageName }) {
	return (
		<>
			<div className='navbar-menu d-flex justify-content-between p-4'>
				<div>{pageName}</div>
				<div className='d-flex'>
					<div className="me-2">user name</div>
					<FaRegUserCircle size={24} />
				</div>
			</div>
			<hr className='m-0 px-3' />
		</>
	);
}
