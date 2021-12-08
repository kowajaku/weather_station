import requests
def wyslij_dane(ip,temp,cis,wilg):
    url=f"http://student.uci.agh.edu.pl/~kowaljak/stacja/?temp={temp}&cis={cis}&wilg={wilg}"
    a=requests.get(url)
    return a

#wyslij_dane("192.168.151.94",12,4,6)