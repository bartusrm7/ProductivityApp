import { RiDeleteBin6Line } from "react-icons/ri";

export default function DeleteGoal({ goalId, refreshData }: { goalId: number; refreshData: () => void }) {
	async function handleDeleteGoal() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/delete-goal", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: goalId }),
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
			<button className='action-btn delete-action-btn' onClick={handleDeleteGoal}>
				<RiDeleteBin6Line size={24} />
			</button>
		</>
	);
}
