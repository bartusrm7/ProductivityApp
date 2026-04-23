import { IoIosArrowUp } from "react-icons/io";

export default function TasksDone() {
	return (
		<div>
			<div className='my-3'>
				<div>
					<div>
						<div className='d-flex align-items-center'>
							<IoIosArrowUp size={24} />
							<h3 className='ms-2 mb-0'>Done</h3>
						</div>
						<hr />
					</div>
					<div></div>
				</div>
			</div>
		</div>
	);
}
