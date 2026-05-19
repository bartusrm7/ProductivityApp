import { RiDeleteBin6Line } from "react-icons/ri";

export default function DeleteHabit({ habitId, refreshData }: { habitId: number; refreshData: () => void }) {
	async function handleDeleteHabit() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/delete-habit", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: habitId }),
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
			<button className='action-btn delete-action-btn' onClick={handleDeleteHabit}>
				<RiDeleteBin6Line size={24} />
			</button>
		</>
	);
}
