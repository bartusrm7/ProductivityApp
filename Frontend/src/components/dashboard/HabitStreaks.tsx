export default function HabitStreaks() {
	async function getStreakTasks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
	}

	return (
		<div className='my-2'>
			<div>🔥 Current best streak: </div>
			<div>🏆 Best streak overall: </div>
		</div>
	);
}
