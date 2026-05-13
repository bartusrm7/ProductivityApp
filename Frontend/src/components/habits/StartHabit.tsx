import { useEffect, useState } from "react";
import { MdDownloadDone } from "react-icons/md";

export default function StartHabit({ habitId }: { habitId: number }) {
	const [habitsData, setHabitsData] = useState<number>();

	async function handleStartHabit() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/habit-status-started", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: habitId }),
			});
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	useEffect(() => {
		setHabitsData(habitId);
	}, [habitId]);

	return (
		<>
			<button className='me-2' onClick={handleStartHabit}>
				<MdDownloadDone size={24} />
			</button>
		</>
	);
}
