import { RiDeleteBin6Line } from "react-icons/ri";

export default function TaskDelete({ taskId, refreshData }: { taskId: number; refreshData: () => void }) {
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
				refreshData();
			}
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	return (
		<>
			<button className='action-btn delete-action-btn' onClick={handleDeleteTask}>
				<RiDeleteBin6Line size={24} />
			</button>
		</>
	);
}
