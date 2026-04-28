import { Button } from "react-bootstrap";
import { RiDeleteBin6Line } from "react-icons/ri";

export default function TaskDelete({ taskId }: { taskId: number }) {
	async function handleDeleteTask() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/delete-task", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: taskId }),
			});
			const data = await response.json();
			if (data.success) {
			}
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	return (
		<>
			<Button className='bg-danger' onClick={handleDeleteTask}>
				<RiDeleteBin6Line size={24} />
			</Button>
		</>
	);
}
